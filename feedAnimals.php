<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['feedAnimals'], $_POST['enclosure_id'])) {
    $enclosure = $zoo->getEnclosureById((int) $_POST['enclosure_id']);

    if ($enclosure !== null) {
        $someoneSleepy = $zoo->getEmployee()->toFeed($enclosure);

        foreach ($enclosure->getAnimals() as $animal) {
            $repository->updateAnimal($animal);
        }

        $redirect = 'main.php?enclosure_id=' . $enclosure->getId();
        if ($someoneSleepy) {
            $redirect .= '&error=sleepy';
        }

        header('location: ' . $redirect);
        exit;
    }
}

header('location: main.php');
exit;
