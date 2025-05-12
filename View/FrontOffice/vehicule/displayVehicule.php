<?php
require_once __DIR__ . '/../../../Controller/vehiculeC.php';
require_once __DIR__ . '/../../../appConfig.php';
if (!isset($id_user)) {
    echo "Erreur : Utilisateur non connecté.";
    exit;
}
$vehiculeController = new VehiculeC();
try {
    $userVehicules = $vehiculeController->listUserVehicules($id_user);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Véhicules</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

    <div class="user-route-cards">
        <h2>Vos Véhicules</h2>
        <?php if (!empty($userVehicules)): ?>
            <?php foreach ($userVehicules as $vehicule): ?>
                <div class="route-card">
                    <h3>Véhicule: <?= htmlspecialchars($vehicule['marque']) ?>         <?= htmlspecialchars($vehicule['modele']) ?></h3>
                    <p><strong>Matricule:</strong> <?= htmlspecialchars($vehicule['matricule']) ?></p>
                    <p><strong>Type:</strong> <?= htmlspecialchars($vehicule['type_vehicule']) ?></p>
                    <p><strong>Nombre de Places:</strong> <?= htmlspecialchars($vehicule['nb_places']) ?></p>
                    <p><strong>Couleur:</strong> <?= htmlspecialchars($vehicule['couleur']) ?></p>
                    <p><strong>Confort:</strong> <?= htmlspecialchars($vehicule['confort']) ?></p>
                    <p><strong>Photo:</strong>
                        <?php if (!empty($vehicule['photo_vehicule'])): ?>
                            <img src="../../assets/uploads/<?= htmlspecialchars($vehicule['photo_vehicule']) ?>"
                                alt="Photo du véhicule" style="width: 100px;">
                        <?php else: ?>
                            Aucune photo disponible.
                        <?php endif; ?>
                    </p>
                    <div class="actions">
                        <button class="btn edit" data-id="<?= $vehicule['id_vehicule'] ?>"
                            data-matricule="<?= htmlspecialchars($vehicule['matricule']) ?>"
                            data-type="<?= htmlspecialchars($vehicule['type_vehicule']) ?>"
                            data-seats="<?= htmlspecialchars($vehicule['nb_places']) ?>"
                            data-color="<?= htmlspecialchars($vehicule['couleur']) ?>"
                            data-marque="<?= htmlspecialchars($vehicule['marque']) ?>"
                            data-modele="<?= htmlspecialchars($vehicule['modele']) ?>"
                            data-confort="<?= htmlspecialchars($vehicule['confort']) ?>">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button class="btn delete" data-id="<?= $vehicule['id_vehicule'] ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez ajouté aucun véhicule pour le moment.</p>
        <?php endif; ?>
        <!-- Modal for Updating Vehicle -->
        <div class="modal" id="ride-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modal-title">Modifier un Véhicule</h2>
                    <button class="close-modal"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <form id="ride-form" method="POST" action="updateVehicule.php" enctype="multipart/form-data">
                        <input type="hidden" id="id_vehicule" name="id_vehicule">
                        <input type="hidden" id="existing-photo" name="existing_photo">

                        <div class="form-group">
                            <label for="ride-matricule">Matricule</label>
                            <input type="text" id="ride-matricule" name="matricule" required>
                            <span id="ride-matricule-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-type">Type de Véhicule</label>
                            <input type="text" id="ride-type" name="type_vehicule" required>
                            <span id="ride-type-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-seats">Nombre de Places</label>
                            <input type="number" id="ride-seats" name="nb_places" required>
                            <span id="ride-seats-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-color">Couleur</label>
                            <input type="text" id="ride-color" name="couleur" required>
                            <span id="ride-color-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-marque">Marque</label>
                            <input type="text" id="ride-marque" name="marque" required>
                            <span id="ride-marque-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-modele">Modèle</label>
                            <input type="text" id="ride-modele" name="modele" required>
                            <span id="ride-modele-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-confort">Confort</label>
                            <input type="text" id="ride-confort" name="confort" required>
                            <span id="ride-confort-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="ride-photo">Photo</label>
                            <input type="file" id="ride-photo" name="photo_vehicule" accept="image/*">
                            <span id="ride-photo-error" class="error-message"></span>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn secondary cancel-btn">Annuler</button>
                            <button type="submit" class="btn primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>