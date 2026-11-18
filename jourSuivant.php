<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null) {
    $zoo->main();
    $repository->saveZooState($zoo);
}

$redirect = 'main.php';
if (isset($_POST['enclosure_id']) && $_POST['enclosure_id'] !== '') {
    $redirect .= '?enclosure_id=' . (int) $_POST['enclosure_id'];
}

header('location: ' . $redirect);
exit;
