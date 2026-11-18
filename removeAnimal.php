<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['removeAnimal'], $_POST['enclosure_id'])) {
    $enclosure = $zoo->getEnclosureById((int) $_POST['enclosure_id']);

    if ($enclosure !== null) {
        $animal = $enclosure->getAnimalById((int) $_POST['removeAnimal']);

        if ($animal !== null) {
            $enclosure->removeAnimal($animal);
            $repository->removeAnimal($enclosure, $animal);
        }

        header('location: main.php?enclosure_id=' . $enclosure->getId());
        exit;
    }
}

header('location: main.php');
exit;
