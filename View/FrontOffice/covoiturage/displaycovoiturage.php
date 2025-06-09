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
                    <div class="route-cities">
                        <span class="departure"
                            style="font-weight: bold;"><?= htmlspecialchars($covoiturage['lieu_depart']) ?></span>
                        <i class="fas fa-long-arrow-alt-right" style="color: #86b391;"></i>
                        <span class="arrival"
                            style="font-weight: bold;"><?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></span>
                    </div>

                    <p><i class="fas fa-calendar-alt" style="color: #86b391;"></i> <strong>Date:</strong>
                        <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                    <p><i class="fas fa-clock" style="color: #86b391;"></i> <strong>Heure:</strong>
                        <?= htmlspecialchars($covoiturage['temps_depart']) ?></p>
                    <p><i class="fas fa-user-friends" style="color: #86b391;"></i> <strong>Places disponibles:</strong>
                        <?= htmlspecialchars($covoiturage['places_dispo']) ?></p>
                    <p><i class="fas fa-money-bill-wave" style="color: #86b391;"></i> <strong>Prix:</strong>
                        <?= htmlspecialchars($covoiturage['prix']) ?> TND</p>
                    <p><i class="fas fa-box-open" style="color: #86b391;"></i> <strong>Colis:</strong>
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
                    <p><i class="fas fa-info-circle" style="color: #86b391;"></i> <strong>Détails:</strong>
                        <?= htmlspecialchars($covoiturage['details'] ?? 'Aucun détail fourni') ?></p>
                    <div class="top-buttons">
                        <!-- Add Voir Météo Button if bad weather -->
                        <button class="icon-btn weather-icon-btn" data-city="<?= htmlspecialchars($covoiturage['lieu_arrivee']) ?>"
                            data-date="<?= htmlspecialchars($covoiturage['date_depart']) ?>">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </button>

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
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun trajet disponible pour le moment.</p>
    <?php endif; ?>
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

<!-- User Modal -->
<div id="user-modal" class="user-modal">
    <div class="user-modal-content">
        <div class="modal-header">
            <h2>Détails du Conducteur</h2>
            <span class="close-user-modal">&times;</span>
        </div>
        <div class="modal-body">
            <img id="user-image" src="" alt="Photo du conducteur" class="driver-profile-img" />
            <div class="article-meta-grid">
                <div><strong>Nom:</strong> <span id="user-nom"></span></div>
                <div><strong>Prénom:</strong> <span id="user-prenom"></span></div>
                <div><strong>Email:</strong> <span id="user-email"></span></div>
                <div><strong>Téléphone:</strong> <span id="user-telephone"></span></div>
            </div>
        </div>
    </div>
</div>

<!-- Vehicule Modal -->
<div id="vehicule-modal" class="vehicule-modal">
    <div class="vehicule-modal-content">
        <div class="modal-header">
            <h2>Détails du Véhicule</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <img id="vehicule-photo" src="" alt="Photo du véhicule" class="vehicle-img" />
            <div class="article-meta-grid">
                <div><strong>Marque:</strong> <span id="vehicule-marque"></span></div>
                <div><strong>Modèle:</strong> <span id="vehicule-modele"></span></div>
                <div><strong>Matricule:</strong> <span id="vehicule-matricule"></span></div>
                <div><strong>Couleur:</strong> <span id="vehicule-couleur"></span></div>
                <div><strong>Nombre de places:</strong> <span id="vehicule-places"></span></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.weather-icon-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const city = btn.getAttribute('data-city');
            // Show loading spinner or modal here if you want
            fetch(`getWeather.php?city=${encodeURIComponent(city)}`)
                .then(res => res.json())
                .then(data => {
                    // Show weather info in your modal
                    // Example: document.querySelector('.temp').textContent = data.temp + '°C';
                });
        });
    });
</script>
<script src="voirvehicule.js"></script>
<script src="driver.js"></script>
<script src="manageRequests.js"></script>