<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos Trajets Populaires</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php
    require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
    require_once __DIR__ . '/../../../appConfig.php';

    // Ensure the user is logged in
    if (!isset($id_user)) {
        echo "Erreur : Utilisateur non connecté.";
        exit;
    }

    $covoiturageController = new CovoiturageC();

    try {
        // Fetch the user's covoiturages
        $userCovoiturages = $covoiturageController->listUserCovoiturages($id_user);

        // Get the current date
        $currentDate = date('Y-m-d');

        // Filter covoiturages to include only recent or future dates
        $userCovoiturages = array_filter($userCovoiturages, function ($covoiturage) use ($currentDate) {
            return $covoiturage['date_depart'] >= $currentDate;
        });

        // Fetch booking requests dynamically from the database
        $bookingRequests = $covoiturageController->getBookingRequests($id_user);

    } catch (Exception $e) {
        echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    ?>

    <div class="user-route-cards">
        <h2>Vos Trajets Populaires</h2>
        <?php if (!empty($userCovoiturages)): ?>
            <?php foreach ($userCovoiturages as $covoiturage): ?>
                <br>
                <div class="route-card">
                    <h3>Trajet de <?= htmlspecialchars($covoiturage['lieu_depart']) ?> à
                        <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?>
                    </h3>
                    <!-- Show booking requests -->
                    <?php if (isset($bookingRequests[$covoiturage['id_covoit']])): ?>
                        <button class="icon-btn request-icon-btn"
                            data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>"
                            data-id-user="<?= htmlspecialchars($bookingRequests[$covoiturage['id_covoit']]['id_user']) ?>">
                            <i class="fa-solid fa-bell" style="color: #f52424;"></i>
                        </button>
                    <?php endif; ?>
                    <p><strong>Date:</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                    <p><strong>Heure:</strong> <?= htmlspecialchars($covoiturage['temps_depart']) ?></p>
                    <p><strong>Places disponibles:</strong> <?= htmlspecialchars($covoiturage['places_dispo']) ?></p>
                    <p><strong>Prix:</strong> <?= htmlspecialchars($covoiturage['prix']) ?> TND</p>
                    <p><strong>Colis:</strong>
                        <?php
                        if ($covoiturage['accepte_colis'] == 0) {
                            echo "Colis non acceptés.";
                        } elseif ($covoiturage['accepte_colis'] == 1 && $covoiturage['colis_complet'] == 1) {
                            echo "Livraison de colis possible.";
                        } else {
                            echo "Colis acceptés.";
                        }
                        ?>
                    </p>
                    <p><strong>Détails:</strong> <?= htmlspecialchars($covoiturage['details'] ?? 'Aucun détail fourni') ?></p>
                    <div class="actions">
                        <button class="btn edit" data-id="<?= $covoiturage['id_covoit'] ?>"
                            data-departure="<?= htmlspecialchars($covoiturage['lieu_depart']) ?>"
                            data-destination="<?= htmlspecialchars($covoiturage['lieu_arrivee']) ?>"
                            data-date="<?= htmlspecialchars($covoiturage['date_depart']) ?>"
                            data-time="<?= htmlspecialchars($covoiturage['temps_depart']) ?>"
                            data-seats="<?= htmlspecialchars($covoiturage['places_dispo']) ?>"
                            data-price="<?= htmlspecialchars($covoiturage['prix']) ?>"
                            data-accept-parcels="<?= $covoiturage['accepte_colis'] ?>"
                            data-full-parcels="<?= $covoiturage['colis_complet'] ?>"
                            data-description="<?= htmlspecialchars($covoiturage['details'] ?? '') ?>"
                            data-id-vehicule="<?= htmlspecialchars($covoiturage['id_vehicule'] ?? '') ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn delete" data-id="<?= $covoiturage['id_covoit'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez ajouté aucun trajet pour le moment.</p>
        <?php endif; ?>

        <!-- Modal for Updating Ride -->
        <div class="modal" id="ride-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modal-title">Modifier un Trajet</h2>
                    <button class="close-modal" aria-label="Fermer la fenêtre"><i class="fas fa-times"></i></button>
                </div>

                <div class="modal-body">
                    <form id="edit-ride-form" method="POST" action="UserUpdateCovoiturage.php">
                        <input type="hidden" id="id_covoit" name="id_covoit">

                        <div class="form-group">
                            <label for="ride-departure">Départ</label>
                            <input type="text" id="ride-departure" name="departure">
                            <span class="error-message" id="ride-departure-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="ride-destination">Destination</label>
                            <input type="text" id="ride-destination" name="destination">
                            <span class="error-message" id="ride-destination-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="ride-date-edit">Date</label>
                            <input type="date" id="ride-date-edit" name="date">
                            <span class="error-message" id="ride-date-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="ride-time-edit">Heure</label>
                            <input type="time" id="ride-time-edit" name="time">
                            <span class="error-message" id="ride-time-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="ride-seats">Places disponibles</label>
                            <input type="number" id="ride-seats" name="seats" min="1">
                            <span class="error-message" id="ride-seats-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="ride-price">Prix par place (TND)</label>
                            <input type="number" id="ride-price" name="price" min="0" step="1">
                            <span class="error-message" id="ride-price-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="accept-parcels">Accepte les colis</label>
                            <select id="accept-parcels" name="accept_parcels">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                            <span class="error-message" id="accept-parcels-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="full-parcels">Colis complet</label>
                            <select id="full-parcels" name="full_parcels">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                            <span class="error-message" id="full-parcels-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="ride-description">Description</label>
                            <textarea id="ride-description" name="description" rows="3"
                                placeholder="Détails supplémentaires..."></textarea>
                            <span class="error-message" id="ride-description-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="id_vehicule_edit">Sélectionnez un véhicule</label>
                            <?php if (!empty($vehicules)): ?>
                                <select id="id_vehicule_edit" name="id_vehicule">
                                    <option value="">-- Sélectionnez un véhicule --</option>
                                    <?php foreach ($vehicules as $vehicule): ?>
                                        <option value="<?= htmlspecialchars($vehicule['id_vehicule']) ?>">
                                            <?= htmlspecialchars($vehicule['matricule']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-message" id="id-vehicule-error-edit"></span>
                            <?php else: ?>
                                <p>Vous n'avez pas encore ajouté de véhicule.</p>
                                <a href="../vehicule/index.php" class="btn btn-primary">Ajouter véhicule</a>
                            <?php endif; ?>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-outline cancel-btn">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for Viewing User Details -->
        <div id="client-booked-modal" class="client-booked-modal">
            <div class="client-booked-modal-content">
                <button class="close-client-booked-modal" aria-label="Fermer"><span>&times;</span></button>
                <h2 class="client-booked-modal-title">Détails du Conducteur</h2>
                <p><strong>Nom:</strong> <span id="client-nom"></span></p>
                <p><strong>Prénom:</strong> <span id="client-prenom"></span></p>
                <p><strong>Email:</strong> <span id="client-email"></span></p>
                <p><strong>Téléphone:</strong> <span id="client-telephone"></span></p>
                <div class="client-modal-actions">
                    <button class="btn secondary cancel-btn reject-client-request">Refuser</button>
                    <button class="btn primary accept-client-request">Accepter</button>
                </div>
            </div>
        </div>

        <script src="manageRequests.js"></script>
        <script>
            const modal = document.getElementById('ride-modal');
            const cancelBtn = modal.querySelector('.cancel-btn');
            const closeBtn = modal.querySelector('.close-modal');
            

            function openRideModal() {
                modal.classList.add('active');
            }

            function closeRideModal() {
                modal.classList.remove('active');
            }

            closeBtn.addEventListener('click', closeRideModal);
            cancelBtn.addEventListener('click', closeRideModal);
        </script>

</body>

</html>