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

require_once '../../../Controller/ColisController.php';

$ColisC = new ColisController();

// Check if an ID is provided in the URL
if (!isset($_GET['id_colis'])) {
    die("Invalid Request");
}

$id_colis = $_GET['id_colis'];
$colis = null;

// Fetch the existing colis details
$list = $ColisC->listColis();
foreach ($list as $c) {
    if ($c['id_colis'] == $id_colis) {
        $colis = $c;
        break;
    }
}

if (!$colis) {
    die("Colis not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_covoit = !empty($_POST['id_covoit']) ? $_POST['id_covoit'] : NULL;

    $ColisC->updateColis(
        $id_colis,
        $_POST['id_client'],
        $id_covoit,
        $_POST['statut'],
        $_POST['date_colis'],
        $_POST['longueur'],
        $_POST['largeur'],
        $_POST['hauteur'],
        $_POST['poids'],
        $_POST['lieu_ram'],
        $_POST['lieu_dest'],
        $_POST['latitude_ram'],
        $_POST['longitude_ram'],
        $_POST['latitude_dest'],
        $_POST['longitude_dest'],
        $_POST['prix']
    );

    header("Location: ../colis/ColisList.php"); // Redirect to the colis list
    exit();
}
?>

<div class="route-cards">
    <?php if (!empty($covoiturages)): ?>
        <?php foreach ($covoiturages as $covoiturage): ?>
            <?php if ($covoiturage['date_depart'] >= $currentDate): // Only display future or recent covoiturages ?>
                <div class="route-card">
                    <h3>Trajet de <?= htmlspecialchars($covoiturage['lieu_depart']) ?> à <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></h3>
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
                        <button class="btn btn-primary voir-vehicule-btn" type="button"
                            data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                            Voir Véhicule
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            Véhicule non disponible
                        </button>
                    <?php endif; ?>

                    <!-- Form to select this covoiturage -->
                    <form method="POST" style="text-align: right; margin-top: 10px;">
                        <!-- Hidden inputs with existing colis data -->
                        <input type="hidden" name="id_covoit" value="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                        <input type="hidden" name="id_client" value="<?= htmlspecialchars($colis['id_client']) ?>">
                        <input type="hidden" name="statut" value="<?= htmlspecialchars($colis['statut']) ?>">
                        <input type="hidden" name="date_colis" value="<?= htmlspecialchars($colis['date_colis']) ?>">
                        <input type="hidden" name="longueur" value="<?= htmlspecialchars($colis['longueur']) ?>">
                        <input type="hidden" name="largeur" value="<?= htmlspecialchars($colis['largeur']) ?>">
                        <input type="hidden" name="hauteur" value="<?= htmlspecialchars($colis['hauteur']) ?>">
                        <input type="hidden" name="poids" value="<?= htmlspecialchars($colis['poids']) ?>">
                        <input type="hidden" name="lieu_ram" value="<?= htmlspecialchars($colis['lieu_ram']) ?>">
                        <input type="hidden" name="lieu_dest" value="<?= htmlspecialchars($colis['lieu_dest']) ?>">
                        <input type="hidden" name="latitude_ram" value="<?= htmlspecialchars($colis['latitude_ram']) ?>">
                        <input type="hidden" name="longitude_ram" value="<?= htmlspecialchars($colis['longitude_ram']) ?>">
                        <input type="hidden" name="latitude_dest" value="<?= htmlspecialchars($colis['latitude_dest']) ?>">
                        <input type="hidden" name="longitude_dest" value="<?= htmlspecialchars($colis['longitude_dest']) ?>">
                        <input type="hidden" name="prix" value="<?= htmlspecialchars($colis['prix']) ?>">

                        <button type="submit" class="btn btn-primary">Sélectionner</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Structure (placed OUTSIDE the foreach loop!) -->
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

<script>
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        this.querySelector('button[type="submit"]').disabled = true;
    });
});
</script>

<script src="voirvehicule.js"></script>
