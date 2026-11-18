<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo !== null) {
    $repository->deleteZoo($zoo);
}

header('location: main.php');
exit;
