<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../appConfig.php';
// Ensure the user is logged in
if (!isset($id_user)) {
    echo "Erreur : Vous devez être connecté pour accéder à cette page.";
    exit;
}

$covoiturageController = new CovoiturageC();
try {
    // Fetch the list of covoiturages
    $covoiturages = $covoiturageController->listCovoiturages();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}

// Get the current date
$currentDate = date('Y-m-d');

// Function to check if the weather is bad
function isBadWeather($city)
{
    $apiKey = "8aab6949191302a6a18a11e8f68d5acf";
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . urlencode($city) . "&appid=" . $apiKey;

    $response = file_get_contents($apiUrl);
    if ($response === false) {
        return false; // Assume weather is not bad if API call fails
    }

    $data = json_decode($response, true);
    if (isset($data['main']['temp'], $data['weather'][0]['main'])) {
        $temperature = $data['main']['temp'];
        $condition = $data['weather'][0]['main'];

        // Define bad weather conditions
        if ($temperature > 18 || $condition === "Rain" || $condition === "Drizzle" || $condition === "Thunderstorm") {
            return true;
        }
    }

    return false;
}
?>

<div class="route-cards">
    <?php if (!empty($covoiturages)): ?>
        <?php foreach ($covoiturages as $covoiturage): ?>
            <?php if ($covoiturage['date_depart'] >= $currentDate): // Only display future or recent covoiturages ?>
                <div class="route-card">
                    <div class="top-buttons">
                        <!-- Add Voir Météo Button if bad weather -->
                        <?php if (isBadWeather($covoiturage['lieu_arrivee'])): ?>
                            <button class="icon-btn weather-icon-btn" data-city="<?= htmlspecialchars($covoiturage['lieu_arrivee']) ?>"
                                data-date="<?= htmlspecialchars($covoiturage['date_depart']) ?>">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </button>
                        <?php endif; ?>

                        <!-- Add Voir Véhicule Button -->
                        <?php if (!empty($covoiturage['id_vehicule'])): ?>
                            <button class="icon-btn vehicule-icon-btn"
                                data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                                <i class="fa-solid fa-car" style="color: #63E6BE;"></i>
                            </button>
                        <?php endif; ?>
                        <!-- User Profile Button -->
                        <button class="icon-btn user-icon-btn" data-id-user="<?= htmlspecialchars($covoiturage['id_user']) ?>">
                            <i class="fa-solid fa-user" style="color: #4CAF50;"></i>
                        </button>
                        <!-- Book/Cancel Covoiturage Button -->
                        <?php if ($id_user != $covoiturage['id_user']): ?>
                            <?php
                            // Get the booking status for the current user and covoiturage
                            $bookingStatus = $covoiturageController->getBookingStatus($covoiturage['id_covoit'], $id_user);
                            ?>

                            <?php if ($bookingStatus === 'pending'): ?>
                                <button class="icon-btn book-icon-btn"
                                    data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>"
                                    data-id-user="<?= htmlspecialchars($id_user) ?>" data-booked="true">
                                    <i class="fa-solid fa-pause" style="color: #FFD43B;"></i>
                                </button>
                            <?php elseif ($bookingStatus === 'accepted'): ?>
                                <button class="icon-btn book-icon-btn"
                                    data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>"
                                    data-id-user="<?= htmlspecialchars($id_user) ?>" data-booked="true">
                                    <i class="fa-solid fa-check" style="color: #aaec98;"></i>
                                </button>
                            <?php elseif ($bookingStatus === 'rejected'): ?>
                                <button class="icon-btn book-icon-btn"
                                    data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>"
                                    data-id-user="<?= htmlspecialchars($id_user) ?>" data-booked="true">
                                    <i class="fa-solid fa-face-frown" style="color: #FFD43B;"></i>
                                </button>
                            <?php else: ?>
                                <button class="icon-btn book-icon-btn"
                                    data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>"
                                    data-id-user="<?= htmlspecialchars($id_user) ?>" data-booked="false">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <h3>Trajet de <?= htmlspecialchars($covoiturage['lieu_depart']) ?> à
                        <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?>
                    </h3>
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
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun trajet disponible pour le moment.</p>
    <?php endif; ?>
</div>

<!-- Modal Structure -->
<!-- Modal Structure -->
<div id="vehicule-modal" class="vehicule-modal">
    <div class="vehicule-modal-content">
        <span class="close-modal">&times;</span>
        <h2 class="vehicule-modal-title">Détails du Véhicule</h2>
        <img id="vehicule-photo" src="" alt="Photo du véhicule" />
        <p><strong>Marque:</strong> <span id="vehicule-marque"></span></p>
        <p><strong>Modèle:</strong> <span id="vehicule-modele"></span></p>
        <p><strong>Matricule:</strong> <span id="vehicule-matricule"></span></p>
        <p><strong>Couleur:</strong> <span id="vehicule-couleur"></span></p>
        <p><strong>Nombre de places:</strong> <span id="vehicule-places"></span></p>
    </div>
</div>
<!-- Weather Modal -->
<div id="weatherModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="card">
            <div class="error" style="display: none; color: red; text-align: center; margin-bottom: 10px;">
                Error message will appear here
            </div>
            <div class="weather">
                <img src="./weather-app-img/images/clear.png" class="weather-icon">
                <h1 class="temp">22°C</h1>
                <h2 class="city">Sydney</h2>
                <div class="detail">
                    <div class="col">
                        <img src="./weather-app-img/images/humidity.png">
                        <div>
                            <p class="humidity">15%</p>
                            <p>Humidity</p>
                        </div>
                    </div>
                    <div class="col">
                        <img src="./weather-app-img/images/wind.png">
                        <div>
                            <p class="speed">10km/h</p>
                            <p>Wind Speed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="user-modal" class="user-modal">
    <div class="user-modal-content">
        <span class="close-user-modal">&times;</span>
        <h2 class="user-modal-title">Détails du Conducteur</h2>
        <p><strong>Nom:</strong> <span id="user-nom"></span></p>
        <p><strong>Prénom:</strong> <span id="user-prenom"></span></p>
        <p><strong>Email:</strong> <span id="user-email"></span></p>
        <p><strong>Téléphone:</strong> <span id="user-telephone"></span></p>
    </div>
</div>

<script src="voirvehicule.js"></script>
<script src="driver.js"></script>
<script src="manageRequests.js"></script>