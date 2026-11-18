<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['enclosure'], $_POST['species'], $_POST['weight'], $_POST['height'], $_POST['age'])) {
    $enclosure = $zoo->getEnclosureByName($_POST['enclosure']);

    if ($enclosure === null) {
        header('location: main.php');
        exit;
    }

    if (count($enclosure->getAnimals()) >= $enclosure->getLength()) {
        header('location: main.php?enclosure_id=' . $enclosure->getId() . '&error=notEnoughSpaceAdd');
        exit;
    }

    if ($enclosure->getActualSpecies() === $_POST['species'] || $enclosure->getActualSpecies() === 'none') {
        $weight = (float) $_POST['weight'];
        $height = (float) $_POST['height'];
        $age = (int) $_POST['age'];

        switch ($_POST['species']) {
            case 'tiger':
                $newAnimal = new Tiger(0, $weight, $height, $age);
                break;
            case 'bear':
                $newAnimal = new Bear(0, $weight, $height, $age);
                break;
            case 'fish':
                $newAnimal = new Fish(0, $weight, $height, $age);
                break;
            case 'eagle':
                $newAnimal = new Eagle(0, $weight, $height, $age);
                break;
            default:
                header('location: main.php?enclosure_id=' . $enclosure->getId());
                exit;
        }

        $enclosure->addAnimal($newAnimal);
        $repository->addAnimal($enclosure, $newAnimal);

        header('location: main.php?enclosure_id=' . $enclosure->getId());
        exit;
    }

    header('location: main.php?enclosure_id=' . $enclosure->getId() . '&error=notSameSpecies');
    exit;
}

header('location: main.php');
exit;
