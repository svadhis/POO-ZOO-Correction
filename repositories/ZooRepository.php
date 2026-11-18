<?php

include_once(__DIR__ . '/../config/Database.php');
include_once(__DIR__ . '/../mappers/EmployeeMapper.php');
include_once(__DIR__ . '/../mappers/ZooMapper.php');
include_once(__DIR__ . '/../mappers/EnclosureMapper.php');
include_once(__DIR__ . '/../mappers/AnimalMapper.php');

class ZooRepository
{
    private PDO $pdo;
    private EmployeeMapper $employeeMapper;
    private ZooMapper $zooMapper;
    private EnclosureMapper $enclosureMapper;
    private AnimalMapper $animalMapper;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
        $this->employeeMapper = new EmployeeMapper();
        $this->zooMapper = new ZooMapper();
        $this->enclosureMapper = new EnclosureMapper();
        $this->animalMapper = new AnimalMapper();
    }

    /**
     * Récupère le zoo actuel avec son employé, ses enclos et leurs animaux.
     */
    public function findCurrentZoo(): ?Zoo
    {
        $stmt = $this->pdo->query('SELECT * FROM zoos ORDER BY id DESC LIMIT 1');
        $zooRow = $stmt->fetch();

        if (!$zooRow) {
            return null;
        }

        $empStmt = $this->pdo->prepare('SELECT * FROM employees WHERE id = :id');
        $empStmt->execute(['id' => $zooRow['employee_id']]);
        $empRow = $empStmt->fetch();

        if (!$empRow) {
            return null;
        }

        $employee = $this->employeeMapper->hydrate($empRow);

        $encStmt = $this->pdo->prepare('SELECT * FROM enclosures WHERE zoo_id = :zoo_id ORDER BY id ASC');
        $encStmt->execute(['zoo_id' => $zooRow['id']]);
        $enclosureRows = $encStmt->fetchAll();

        $animalStmt = $this->pdo->prepare('SELECT * FROM animals WHERE enclosure_id = :enclosure_id ORDER BY id ASC');
        $enclosures = [];

        foreach ($enclosureRows as $encRow) {
            $animalStmt->execute(['enclosure_id' => $encRow['id']]);
            $animalRows = $animalStmt->fetchAll();

            $animals = [];
            foreach ($animalRows as $animalRow) {
                $animals[] = $this->animalMapper->hydrate($animalRow);
            }

            $enclosures[] = $this->enclosureMapper->hydrate($encRow, $animals);
        }

        return $this->zooMapper->hydrate($zooRow, $employee, $enclosures);
    }

    /**
     * Crée un employé et son zoo en base de données.
     */
    public function createZoo(Zoo $zoo): Zoo
    {
        $this->pdo->beginTransaction();

        try {
            $employee = $zoo->getEmployee();

            $empStmt = $this->pdo->prepare(
                'INSERT INTO employees (name, age, gender) VALUES (:name, :age, :gender)'
            );
            $empStmt->execute([
                'name' => $employee->getName(),
                'age' => $employee->getAge(),
                'gender' => $employee->getGender(),
            ]);
            $employee->setId((int) $this->pdo->lastInsertId());

            $zooStmt = $this->pdo->prepare(
                'INSERT INTO zoos (name, max_enclosure, employee_id) VALUES (:name, :max_enclosure, :employee_id)'
            );
            $zooStmt->execute([
                'name' => $zoo->getName(),
                'max_enclosure' => $zoo->getMaxEnclosure(),
                'employee_id' => $employee->getId(),
            ]);
            $zoo->setId((int) $this->pdo->lastInsertId());

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $zoo;
    }

    /**
     * Supprime le zoo (et en cascade ses enclos, animaux, ainsi que l'employé).
     */
    public function deleteZoo(Zoo $zoo): void
    {
        $this->pdo->beginTransaction();

        try {
            if ($zoo->getId() !== null) {
                $stmt = $this->pdo->prepare('DELETE FROM zoos WHERE id = :id');
                $stmt->execute(['id' => $zoo->getId()]);
            }

            if ($zoo->getEmployee()->getId() !== null) {
                $stmt = $this->pdo->prepare('DELETE FROM employees WHERE id = :id');
                $stmt->execute(['id' => $zoo->getEmployee()->getId()]);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Ajoute un enclos à un zoo en base de données.
     */
    public function addEnclosure(int $zooId, Enclosure $enclosure): Enclosure
    {
        $type = get_class($enclosure);
        $height = ($enclosure instanceof Aviary) ? $enclosure->getHeight() : null;
        $topCleanliness = ($enclosure instanceof Aviary) ? $enclosure->getTopCleanliness() : null;
        $salinity = ($enclosure instanceof Aquarium) ? $enclosure->getSalinity() : null;

        $stmt = $this->pdo->prepare(
            'INSERT INTO enclosures (zoo_id, type, name, length, actual_species, cleanliness, height, top_cleanliness, salinity)
             VALUES (:zoo_id, :type, :name, :length, :actual_species, :cleanliness, :height, :top_cleanliness, :salinity)'
        );
        $stmt->execute([
            'zoo_id' => $zooId,
            'type' => $type,
            'name' => $enclosure->getName(),
            'length' => $enclosure->getLength(),
            'actual_species' => $enclosure->getActualSpecies(),
            'cleanliness' => $enclosure->getCleanliness(),
            'height' => $height,
            'top_cleanliness' => $topCleanliness,
            'salinity' => $salinity,
        ]);

        $enclosure->setId((int) $this->pdo->lastInsertId());
        $enclosure->setZooId($zooId);

        return $enclosure;
    }

    /**
     * Met à jour un enclos en base de données.
     */
    public function updateEnclosure(Enclosure $enclosure): void
    {
        $topCleanliness = ($enclosure instanceof Aviary) ? $enclosure->getTopCleanliness() : null;

        $stmt = $this->pdo->prepare(
            'UPDATE enclosures
             SET actual_species = :actual_species,
                 cleanliness = :cleanliness,
                 top_cleanliness = :top_cleanliness
             WHERE id = :id'
        );
        $stmt->execute([
            'actual_species' => $enclosure->getActualSpecies(),
            'cleanliness' => $enclosure->getCleanliness(),
            'top_cleanliness' => $topCleanliness,
            'id' => $enclosure->getId(),
        ]);
    }

    /**
     * Ajoute un animal dans un enclos en base de données.
     */
    public function addAnimal(Enclosure $enclosure, Animal $animal): Animal
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO animals (enclosure_id, species, weight, height, age, is_hungry, is_sleeping, is_sick)
                 VALUES (:enclosure_id, :species, :weight, :height, :age, :is_hungry, :is_sleeping, :is_sick)'
            );
            $stmt->execute([
                'enclosure_id' => $enclosure->getId(),
                'species' => $animal->getSpecies(),
                'weight' => $animal->getWeight(),
                'height' => $animal->getHeight(),
                'age' => $animal->getAge(),
                'is_hungry' => (int) $animal->getIsHungry(),
                'is_sleeping' => (int) $animal->getIsSleeping(),
                'is_sick' => (int) $animal->getIsSick(),
            ]);

            $animal->setId((int) $this->pdo->lastInsertId());
            $animal->setEnclosureId($enclosure->getId());

            $this->updateEnclosure($enclosure);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $animal;
    }

    /**
     * Met à jour l'état d'un animal en base de données.
     */
    public function updateAnimal(Animal $animal): void
    {
        // TODO
    }

    /**
     * Supprime un animal et met à jour l'espèce actuelle de l'enclos.
     */
    public function removeAnimal(Enclosure $enclosure, Animal $animal): void
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('DELETE FROM animals WHERE id = :id');
            $stmt->execute(['id' => $animal->getId()]);

            $this->updateEnclosure($enclosure);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Déplace un animal d'un enclos à un autre en base de données.
     */
    public function moveAnimal(Enclosure $enclosureFrom, Animal $animal, Enclosure $enclosureTo): void
    {
        $this->pdo->beginTransaction();

        try {
            // TODO
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Sauvegarde l'état complet du zoo (enclos et animaux), par exemple lors du passage au jour suivant.
     */
    public function saveZooState(Zoo $zoo): void
    {
        $this->pdo->beginTransaction();

        try {
            foreach ($zoo->getEnclosures() as $enclosure) {
                $this->updateEnclosure($enclosure);
                foreach ($enclosure->getAnimals() as $animal) {
                    $this->updateAnimal($animal);
                }
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
