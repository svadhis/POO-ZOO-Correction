<?php

include_once('./repositories/ZooRepository.php');

if (
    isset($_POST['name'], $_POST['maxEnclosure'], $_POST['employee'], $_POST['employeeAge'], $_POST['gender'])
) {
    $employee = new Employee(
        trim($_POST['employee']),
        (int) $_POST['employeeAge'],
        $_POST['gender']
    );
    $zoo = new Zoo(
        trim($_POST['name']),
        $employee,
        (int) $_POST['maxEnclosure']
    );

    $repository = new ZooRepository();
    $repository->createZoo($zoo);
}

header('location: main.php');
exit;
