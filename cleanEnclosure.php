<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null && isset($_POST['cleanEnclosure'], $_POST['enclosure_id'])) {
    $enclosure = $zoo->getEnclosureById((int) $_POST['enclosure_id']);

    if ($enclosure !== null) {
        if ($enclosure->getActualSpecies() === 'none') {
            $enclosure->cleaning();
            $repository->updateEnclosure($enclosure);
            header('location: main.php?enclosure_id=' . $enclosure->getId());
            exit;
        }

        header('location: main.php?enclosure_id=' . $enclosure->getId() . '&error=notEmpty');
        exit;
    }
}

header('location: main.php');
exit;
