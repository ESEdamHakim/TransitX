<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../Controller/ColisController.php';

$covoiturageController = new CovoiturageC();
$ColisC = new ColisController();

try {
    // Fetch the list of covoiturages
    $covoiturages = $covoiturageController->listCovoiturages();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}

// Get current date
$currentDate = date('Y-m-d');

// Check if an ID is provided in the URL
if (!isset($_GET['id_colis'])) {
    die("Invalid Request");
}

$id_colis = $_GET['id_colis'];
$colis = null;

// Fetch the existing colis
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
    if (isset($_POST['id_covoit'])) {
        $id_covoit = $_POST['id_covoit'];

        // Update colis with the selected covoiturage
        $ColisC->updateColis(
            $colis['id_colis'],
            $colis['id_client'],
            $id_covoit, // updated covoit
            $colis['statut'],
            $colis['date_colis'],
            $colis['longueur'],
            $colis['largeur'],
            $colis['hauteur'],
            $colis['poids'],
            $colis['lieu_ram'],
            $colis['lieu_dest'],
            $colis['latitude_ram'],
            $colis['longitude_ram'],
            $colis['latitude_dest'],
            $colis['longitude_dest'],
            $colis['prix']
        );

        // Redirect after successful update
        header("Location: ../colis/ColisList.php?success=1");
        exit();
    }
}
?>

<div class="route-cards">
    <?php
    $matchingCovoiturages = [];

    // Filter covoiturages that match the colis criteria
    foreach ($covoiturages as $covoiturage) {
        if (
            $covoiturage['date_depart'] == $colis['date_colis'] &&
            $covoiturage['lieu_depart'] == $colis['lieu_ram'] &&
            $covoiturage['lieu_arrivee'] == $colis['lieu_dest']
        ) {
            $matchingCovoiturages[] = $covoiturage;
        }
    }
    ?>

    <?php if (count($covoiturages) === 0): ?>
        <!-- Case 1: Table is empty -->
        <div class="empty-table" style="text-align: center; margin-top: 30px;">
            <h4>Il n'y a actuellement aucun covoiturage enregistré.</h4>
            <p>Ajoutez un nouveau covoiturage pour commencer à livrer les colis.</p>
            <a href="../covoiturage/addCovoiturage.php" class="btn btn-success">Créer un covoiturage</a>
        </div>

    <?php elseif (!empty($matchingCovoiturages)): ?>
        <!-- Case 2: Matching covoiturages exist -->
        <?php foreach ($matchingCovoiturages as $covoiturage): ?>
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
                    <button class="btn btn-primary voir-vehicule-btn" type="button"
                        data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                        Voir Véhicule
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        Véhicule non disponible
                    </button>
                <?php endif; ?>

                <form method="POST" style="text-align: right; margin-top: 10px;">
                    <input type="hidden" name="id_covoit" value="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                    <input type="hidden" name="id_colis" value="<?= htmlspecialchars($id_colis) ?>">
                    <button type="submit" class="btn btn-primary">Sélectionner</button>
                </form>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!-- Case 3: Covoiturages exist but none match -->
        <div class="no-results" style="text-align: center; margin-top: 30px;">
            <h4>Aucun covoiturage trouvé pour ce colis à cette date et trajet.</h4>
            <br>
            <p></p>
            <a href="../colis/ColisList.php" class="btn btn-primary">Mes Colis</a>
        </div>
    <?php endif; ?>
</div>

<script>
    // Disable the submit button after clicking to avoid double submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            this.querySelector('button[type="submit"]').disabled = true;
        });
    });
</script>

<script src="voirvehicule.js"></script>