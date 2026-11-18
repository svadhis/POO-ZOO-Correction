<?php

include_once('./repositories/ZooRepository.php');

// TODO

if ($zoo !== null) {
    $employee = $zoo->getEmployee();
    $checkingEnclosure = null;

    if (isset($_GET['enclosure_id'])) {
        $checkingEnclosure = // TODO
    }

    $error = $_GET['error'] ?? null;

    include('./structure/top.html');
    ?>

    <section>
        <div class="container">
            <div class="mt-5 d-flex justify-content-evenly">
                <div class="card" style="width: 22rem;">
                    <?php if ($employee->getGender() === 'female') { ?>
                        <img src="/img/woman.png" class="card-img-top" alt="woman-employee">
                    <?php } else { ?>
                        <img src="/img/man.png" class="card-img-top" alt="man-employee">
                    <?php } ?>

                    <div class="card-body">
                        <?php if ($employee->getGender() === 'female') { ?>
                            <h5 class="card-title mb-3">Employée du Zoo</h5>
                        <?php } else { ?>
                            <h5 class="card-title mb-3">Employé du Zoo</h5>
                        <?php } ?>

                        <p class="card-text">Nom : <?php echo htmlspecialchars($employee->getName()); ?></p>
                        <p class="card-text">Âge : <?php echo $employee->getAge(); ?> ans</p>

                        <hr>

                        <!-- check enclosure -->
                        <form action="checkEnclosure.php" method="post" class="d-flex align-items-center justify-content-center">
                            <?php if (count($zoo->getEnclosures()) > 0) { ?>
                                <label for="checkingEnclosure" class="form-label me-3">Liste enclos</label>

                                <select class="form-select me-3" name="checkingEnclosure" id="checkingEnclosure">
                                    <?php foreach ($zoo->getEnclosures() as $enclosure) { ?>
                                        <option value="<?php echo htmlspecialchars($enclosure->getName()); ?>"
                                            <?php echo ($checkingEnclosure !== null && $checkingEnclosure->getId() === $enclosure->getId()) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($enclosure->getName()); ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <button type="submit" class="btn btn-primary">Examiner</button>
                            <?php } else { ?>
                                <p>Aucun enclos</p>
                            <?php } ?>
                        </form>

                        <?php if (count($zoo->getEnclosures()) !== 0) { ?>
                            <hr>

                            <!-- add animal -->
                            <form action="formAnimal.php" method="post" class="d-flex align-items-center justify-content-between">
                                <label for="addAnimal" class="form-label me-3">Choix enclos :</label>

                                <select class="form-select me-3" name="addAnimal" id="addAnimal">
                                    <?php foreach ($zoo->getEnclosures() as $enclosure) { ?>
                                        <option value="<?php echo htmlspecialchars($enclosure->getName()); ?>">
                                            <?php echo htmlspecialchars($enclosure->getName()); ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <button type="submit" class="btn btn-primary">Ajouter animal</button>
                            </form>

                            <?php if ($error === 'notSameSpecies') { ?>
                                <p class="card-text text-danger mt-2">L'animal ne peut être ajouté car il y a déjà un animal d'une autre espèce dans l'enclos</p>
                            <?php } elseif ($error === 'notEnoughSpaceAdd') { ?>
                                <p class="card-text text-danger mt-2">L'enclos est déjà plein</p>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>

                <div class="card" style="width: 30rem;">
                    <img src="/img/zoo.png" class="card-img-top" alt="zoo">

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-3">Zoo</h5>
                                <p class="card-text">Nom : <?php echo htmlspecialchars($zoo->getName()); ?></p>
                                <p class="card-text">Enclos : <?php echo count($zoo->getEnclosures()); ?>/<?php echo $zoo->getMaxEnclosure(); ?></p>
                            </div>
                            <button type="button" class="btn btn-danger h-50" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Nouveau Zoo
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title fs-5" id="exampleModalLabel">ATTENTION !</h2>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Cette action supprimera votre zoo, êtes-vous sûr ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                                        <form action="deleteZoo.php" method="post" class="d-flex align-items-center justify-content-center">
                                            <button type="submit" class="btn btn-danger">Accepter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <form action="formEnclosure.php" method="post" class="d-flex align-items-center justify-content-center">
                            <label for="newEnclosure" class="form-label">Nouvel Enclos</label>

                            <select class="form-select me-3" name="newEnclosure" id="newEnclosure">
                                <option value="classicEnclosure">Enclos Classique</option>
                                <option value="aviary">Volière</option>
                                <option value="aquarium">Aquarium</option>
                            </select>

                            <button type="submit" class="btn btn-primary">Créer</button>
                        </form>

                        <?php if ($error === 'maxEnclosure') { ?>
                            <p class="card-text text-danger mt-2">Nombre maximum d'enclos atteint pour ce zoo</p>
                        <?php } ?>

                        <hr>

                        <p class="card-text">Nombre d'animal : <?php $zoo->countAnimals(); ?></p>

                        <hr>

                        <?php if (count($zoo->getEnclosures()) > 0) { ?>
                            <form action="jourSuivant.php" method="post" class="d-flex align-items-center justify-content-center">
                                <?php if ($checkingEnclosure !== null) { ?>
                                    <input type="hidden" name="enclosure_id" value="<?php echo $checkingEnclosure->getId(); ?>">
                                <?php } ?>
                                <button type="submit" class="btn btn-primary">Jour suivant</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php if ($checkingEnclosure !== null) { ?>
                <div class="container mt-5">
                    <div class="card d-flex flex-row" style="width: 100%;">
                        <div class="card-body-custom" style="width: 30%;">
                            <h5 class="card-title mb-3">Enclos <?php echo htmlspecialchars($checkingEnclosure->getName()); ?></h5>
                            <p class="card-text">Type : <?php echo htmlspecialchars(get_class($checkingEnclosure)); ?></p>
                            <p class="card-text">Taille : <?php echo $checkingEnclosure->getLength(); ?></p>
                            <p class="card-text">Espèce : <?php echo htmlspecialchars($checkingEnclosure->getActualSpecies()); ?></p>
                            <p class="card-text">Etat : <?php echo htmlspecialchars($checkingEnclosure->getCleanliness()); ?></p>

                            <?php if ($checkingEnclosure instanceof Aviary) { ?>
                                <p class="card-text">Hauteur : <?php echo $checkingEnclosure->getHeight(); ?> m</p>
                                <p class="card-text">Etat du haut : <?php echo htmlspecialchars($checkingEnclosure->getTopCleanliness()); ?></p>
                            <?php } elseif ($checkingEnclosure instanceof Aquarium) { ?>
                                <p class="card-text">Salinité : <?php echo $checkingEnclosure->getSalinity(); ?> g/kg</p>
                            <?php } ?>

                            <?php if ($checkingEnclosure->getCleanliness() !== 'clean') { ?>
                                <form action="cleanEnclosure.php" method="post" class="d-flex flex-column align-items-start gap-2">
                                    <input id="cleanEnclosure" name="cleanEnclosure" type="hidden" value="cleanEnclosure">
                                    <input name="enclosure_id" type="hidden" value="<?php echo $checkingEnclosure->getId(); ?>">

                                    <?php if ($error === 'notEmpty') { ?>
                                        <p class="card-text text-danger">L'enclos ne peut être nettoyé que s'il est vide</p>
                                    <?php } ?>

                                    <button type="submit" class="btn btn-primary">Nettoyer</button>
                                </form>
                            <?php } ?>
                        </div>

                        <!-- LES ANIMAUX -->
                        <div class="card-body d-flex flex-column align-items-center mb-5">
                            <h5 class="card-title mb-3">Animaux</h5>
                            <div class="w-100 d-flex flex-row flex-wrap justify-content-between gap-3 mb-3">
                                <?php if (count($checkingEnclosure->getAnimals()) !== 0) {
                                    foreach ($checkingEnclosure->getAnimals() as $animal) { ?>
                                        <div class="border p-3">
                                            <p class="card-text">Espèce : <?php echo htmlspecialchars($animal->getSpecies()); ?></p>
                                            <p class="card-text">Poids : <?php echo $animal->getWeight(); ?></p>
                                            <p class="card-text">Taille : <?php echo $animal->getHeight(); ?></p>
                                            <p class="card-text">Age : <?php echo $animal->getAge(); ?></p>
                                            <p class="card-text">Faim : <?php echo $animal->getIsHungry() ? 'affamé' : 'rassasié'; ?></p>
                                            <p class="card-text">Dort : <?php echo $animal->getIsSleeping() ? 'endormi' : 'éveillé'; ?></p>
                                            <p class="card-text">Malade : <?php echo $animal->getIsSick() ? 'malade' : 'bonne santé'; ?></p>

                                            <div class="d-flex justify-content-between gap-3">
                                                <!-- RETIRER UN ANIMAL -->
                                                <form action="removeAnimal.php" method="post" class="d-flex align-items-center justify-content-center">
                                                    <input name="enclosure_id" type="hidden" value="<?php echo $checkingEnclosure->getId(); ?>">
                                                    <input id="removeAnimal-<?php echo $animal->getId(); ?>" name="removeAnimal" type="hidden" value="<?php echo $animal->getId(); ?>">
                                                    <button type="submit" class="btn btn-danger">Retirer</button>
                                                </form>

                                                <!-- MOVE UN ANIMAL -->
                                                <button type="button" class="btn btn-success h-50" data-bs-toggle="modal" data-bs-target="#moveAnimal-<?php echo $animal->getId(); ?>">
                                                    Déplacer
                                                </button>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="moveAnimal-<?php echo $animal->getId(); ?>" tabindex="-1" aria-labelledby="moveAnimalTitle-<?php echo $animal->getId(); ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-title fs-5" id="moveAnimalTitle-<?php echo $animal->getId(); ?>">Déplacer un animal</h2>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Vers quel enclos voulez-vous envoyer l'animal ?

                                                            <form action="moveAnimal.php" method="post" class="d-flex align-items-center justify-content-center mt-3">
                                                                <select class="form-select me-3" name="moveAnimalTo">
                                                                    <?php foreach ($zoo->getEnclosures() as $enclosure) { ?>
                                                                        <option value="<?php echo htmlspecialchars($enclosure->getName()); ?>">
                                                                            <?php echo htmlspecialchars($enclosure->getName()); ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>

                                                                <input name="moveAnimal" type="hidden" value="<?php echo $animal->getId(); ?>">
                                                                <input name="moveAnimalFrom" type="hidden" value="<?php echo htmlspecialchars($checkingEnclosure->getName()); ?>">

                                                                <button type="submit" class="btn btn-success">Déplacer</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div>
                                        <p class="card-text">Aucun animal.</p>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if (count($checkingEnclosure->getAnimals()) !== 0) { ?>
                                <form action="feedAnimals.php" method="post" class="d-flex flex-column align-items-center gap-2">
                                    <input id="feedAnimals" name="feedAnimals" type="hidden" value="feedAnimals">
                                    <input name="enclosure_id" type="hidden" value="<?php echo $checkingEnclosure->getId(); ?>">

                                    <?php if ($error === 'sleepy') { ?>
                                        <p class="card-text text-danger">Certains animaux dormaient donc ils n'ont pas été nourris</p>
                                    <?php } ?>

                                    <button type="submit" class="btn btn-primary">Nourrir les animaux</button>
                                </form>
                            <?php } ?>

                            <?php if ($error === 'notEnoughSpaceMove') { ?>
                                <p class="card-text text-danger mt-2">Il n'y a pas assez d'espace dans l'enclos destinataire</p>
                            <?php } elseif ($error === 'notSameSpeciesMove') { ?>
                                <p class="card-text text-danger mt-2">L'animal ne peut être ajouté car il y a déjà un animal d'une autre espèce dans l'enclos destinataire</p>
                            <?php } elseif ($error === 'incompatibleEnclosureMove') { ?>
                                <p class="card-text text-danger mt-2">Cet enclos n'est pas compatible avec le type de cet animal</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <?php
    include('./structure/bottom.html');
} else {
    include_once('formZoo.php');
}
