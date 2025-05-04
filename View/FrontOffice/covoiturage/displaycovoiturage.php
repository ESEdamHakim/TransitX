<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';

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
?>

<div class="route-cards">
    <?php if (!empty($covoiturages)): ?>
        <?php foreach ($covoiturages as $covoiturage): ?>
            <?php if ($covoiturage['date_depart'] >= $currentDate): // Only display future or recent covoiturages ?>
                <div class="route-card">
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
                    <?php if (!empty($covoiturage['id_vehicule'])): ?>
                        <button class="btn btn-primary voir-vehicule-btn"
                            data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                            Voir Véhicule
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            Véhicule non disponible
                        </button>
                    <?php endif; ?>
                    <!-- Add Voir Météo Button -->
            <button class="btn btn-info voir-meteo-btn"
                data-city="<?= htmlspecialchars($covoiturage['lieu_arrivee']) ?>"
                data-date="<?= htmlspecialchars($covoiturage['date_depart']) ?>">
                Voir Météo
            </button>
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

<script src="voirvehicule.js"></script>
