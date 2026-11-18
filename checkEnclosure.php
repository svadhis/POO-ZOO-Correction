<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['checkingEnclosure'])) {
    $enclosure = $zoo->getEnclosureByName($_POST['checkingEnclosure']);
    if ($enclosure !== null) {
        header('location: main.php?enclosure_id=' . $enclosure->getId());
        exit;
    }
}

header('location: main.php');
exit;
