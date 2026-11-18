<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['name'])) {
    if (count($zoo->getEnclosures()) >= $zoo->getMaxEnclosure()) {
        header('location: main.php?error=maxEnclosure');
        exit;
    }

    $name = trim($_POST['name']);

    if (isset($_POST['height'])) {
        $enclosure = new Aviary((float) $_POST['height'], $name);
    } elseif (isset($_POST['salinity'])) {
        $enclosure = new Aquarium((float) $_POST['salinity'], $name);
    } else {
        $enclosure = new Enclosure($name);
    }

    $zoo->addEnclosure($enclosure);
    $repository->addEnclosure($zoo->getId(), $enclosure);

    header('location: main.php?enclosure_id=' . $enclosure->getId());
    exit;
}

header('location: main.php');
exit;
