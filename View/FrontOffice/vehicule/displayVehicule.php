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
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>

<body>


    <div class="user-route-cards">
        <?php if (!empty($userVehicules)): ?>
            <?php foreach ($userVehicules as $vehicule): ?>
                <div class="route-card">
                    <h3>
                        <i class="fas fa-car" style="color: #86b391;"></i>
                        Véhicule: <?= htmlspecialchars($vehicule['marque']) ?>         <?= htmlspecialchars($vehicule['modele']) ?>
                    </h3>
                    <p>
                        <i class="fas fa-id-card" style="color: #86b391;"></i>
                        <strong>Matricule:</strong> <?= htmlspecialchars($vehicule['matricule']) ?>
                    </p>
                    <p>
                        <i class="fas fa-car-side" style="color: #86b391;"></i>
                        <strong>Type:</strong> <?= htmlspecialchars($vehicule['type_vehicule']) ?>
                    </p>
                    <p>
                        <i class="fas fa-users" style="color: #86b391;"></i>
                        <strong>Nombre de Places:</strong> <?= htmlspecialchars($vehicule['nb_places']) ?>
                    </p>
                    <p>
                        <i class="fas fa-palette" style="color: #86b391;"></i>
                        <strong>Couleur:</strong> <?= htmlspecialchars($vehicule['couleur']) ?>
                    </p>
                    <p>
                        <i class="fas fa-couch" style="color: #86b391;"></i>
                        <strong>Confort:</strong> <?= htmlspecialchars($vehicule['confort']) ?>
                    </p>
                    <p>
                        <i class="fas fa-image" style="color: #86b391;"></i>
                        <strong>Photo:</strong>
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
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn delete" data-id="<?= $vehicule['id_vehicule'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <br>
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
                        <div class="form-row">
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
                        </div>
                        <div class="form-row">
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
                        </div>
                        <div class="form-row">
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
                        </div>
                        <div class="form-row">
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
                        </div>
                        <div class="modal-buttons">
                            <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal for Vehicle -->
    <div id="delete-confirm-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmer la suppression</h2>
                <button class="close-delete-modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce véhicule ? Cette action est irréversible.</p>
            </div>
            <div class="modal-buttons">
                <button class="btn btn-secondary cancel-delete-btn" type="button">Annuler</button>
                <form id="delete-form" method="POST" action="DeleteVehicule.php" style="display:inline;">
                    <input type="hidden" name="id_vehicule" id="delete-vehicule-id">
                    <button type="submit" class="btn btn-primary">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Open delete modal and set vehicule ID
        document.querySelectorAll('.btn.delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const vehiculeId = this.getAttribute('data-id');
                document.getElementById('delete-vehicule-id').value = vehiculeId;
                document.getElementById('delete-confirm-modal').style.display = 'block';
            });
        });

        // Close modal on close or cancel
        document.querySelector('.close-delete-modal').onclick =
            document.querySelector('.cancel-delete-btn').onclick = function () {
                document.getElementById('delete-confirm-modal').style.display = 'none';
                document.getElementById('delete-vehicule-id').value = '';
            };

        // Optional: close modal when clicking outside modal-content
        window.addEventListener('click', function (e) {
            const modal = document.getElementById('delete-confirm-modal');
            if (e.target === modal) {
                modal.style.display = 'none';
                document.getElementById('delete-vehicule-id').value = '';
            }
        });
    </script>
    <script>
        const modal = document.getElementById('ride-modal');
        const closeBtn = modal.querySelector('.close-modal');
        const cancelBtn = modal.querySelector('.btn.btn-outline'); // the "Annuler" button

        // Show modal (call this from your "Edit" button)
        function openRideModal() {
            modal.classList.add('active');
        }

        // Close modal on close button or cancel
        function closeRideModal() {
            modal.classList.remove('active');
        }

        // Event Listeners
        closeBtn.addEventListener('click', closeRideModal);
        cancelBtn.addEventListener('click', closeRideModal);
    </script>

</body>

</html>