<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['moveAnimal'], $_POST['moveAnimalTo'], $_POST['moveAnimalFrom'])) {
    $enclosureFrom = $zoo->getEnclosureByName($_POST['moveAnimalFrom']);
    $enclosureTo = $zoo->getEnclosureByName($_POST['moveAnimalTo']);

    if ($enclosureFrom === null || $enclosureTo === null) {
        header('location: main.php');
        exit;
    }

    $animal = $enclosureFrom->getAnimalById((int) $_POST['moveAnimal']);

    if ($animal === null) {
        header('location: main.php?enclosure_id=' . $enclosureFrom->getId());
        exit;
    }

    // Vérification de la compatibilité de type d'enclos
    $isCompatibleType = (
        ($enclosureTo instanceof Aquarium && $animal instanceof Marine) ||
        ($enclosureTo instanceof Aviary && $animal instanceof Aerial) ||
        (get_class($enclosureTo) === 'Enclosure' && $animal instanceof Terrestrial)
    );

    if (!$isCompatibleType) {
        header('location: main.php?enclosure_id=' . $enclosureFrom->getId() . '&error=incompatibleEnclosureMove');
        exit;
    }

    if ($enclosureTo->getActualSpecies() === $animal->getSpecies() || $enclosureTo->getActualSpecies() === 'none') {
        if ($enclosureTo->getLength() > count($enclosureTo->getAnimals())) {
            $zoo->getEmployee()->moveAnimal($enclosureFrom, $animal, $enclosureTo);
            $repository->moveAnimal($enclosureFrom, $animal, $enclosureTo);

            header('location: main.php?enclosure_id=' . $enclosureFrom->getId());
            exit;
        }

        header('location: main.php?enclosure_id=' . $enclosureFrom->getId() . '&error=notEnoughSpaceMove');
        exit;
    }

    header('location: main.php?enclosure_id=' . $enclosureFrom->getId() . '&error=notSameSpeciesMove');
    exit;
}

header('location: main.php');
exit;
