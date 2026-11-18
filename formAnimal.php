<?php

include_once('./repositories/ZooRepository.php');

$repository = new ZooRepository();
$zoo = $repository->findCurrentZoo();

if ($zoo === null || !isset($_POST['addAnimal'])) {
    header('location: main.php');
    exit;
}

$enclosure = $zoo->getEnclosureByName($_POST['addAnimal']);

if ($enclosure === null) {
    header('location: main.php');
    exit;
}

$typeEnclosure = get_class($enclosure);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un animal</title>

    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">

</head>
<body>
    <section>
        <div class="container mt-5">
            <form action="addAnimal.php" method="post">
                <div class="mb-3">

                    <input id="enclosure" name="enclosure" type="hidden" value="<?php echo htmlspecialchars($enclosure->getName()); ?>">

                    <label for="species" class="form-label">Espèce : </label>

                    <select class="form-select me-3" name="species" id="species">
                        <?php if ($typeEnclosure === 'Enclosure') { ?>
                            <option value="tiger">Tigre</option>
                            <option value="bear">Ours</option>
                        <?php } elseif ($typeEnclosure === 'Aquarium') { ?>
                            <option value="fish">Poisson</option>
                        <?php } elseif ($typeEnclosure === 'Aviary') { ?>
                            <option value="eagle">Aigle</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="weight" class="form-label">Poids (en kg) de l'animal : </label>
                    <input class="form-control" type="number" name="weight" id="weight" min="1" max="500" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="height" class="form-label">Taille (en mètre) de l'animal : </label>
                    <input class="form-control" type="number" name="height" id="height" min="0" max="5" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age (en année) de l'animal : </label>
                    <input class="form-control" type="number" name="age" id="age" min="1" max="50" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Créer</button>
            </form>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
