<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';

$covoiturageController = new CovoiturageC();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['departure']) || isset($_GET['destination']) || isset($_GET['date']))) {
    $departure = filter_input(INPUT_GET, 'departure', FILTER_SANITIZE_STRING);
    $destination = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);

    try {
        // Fetch search results
        $results = $covoiturageController->searchCovoiturages($departure, $destination, $date);
    } catch (Exception $e) {
        echo '<p>Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>

<div class="route-cards-container">
    <button id="close-search-results" class="btn btn-secondary" style="margin-bottom: 10px;">
        Fermer les résultats
    </button>
    <div class="route-cards">
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $covoiturage): ?>
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
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun trajet trouvé pour votre recherche.</p>
        <?php endif; ?>
    </div>
</div>

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
<script src="voirvehicule.js"></script>
<script>
   
    document.addEventListener("DOMContentLoaded", () => {
        const closeButton = document.getElementById("close-search-results");
    const searchForm = document.querySelector(".search-form");

    if (closeButton) {
        closeButton.addEventListener("click", () => {
            // Reset the search form
            if (searchForm) {
                searchForm.reset();
            }

            // Clear the search results by reloading the page without query parameters
            window.location.href = window.location.pathname;
        });
    }
});

</script>