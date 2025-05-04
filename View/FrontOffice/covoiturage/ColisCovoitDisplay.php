<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../Controller/ColisController.php';

$covoiturageController = new CovoiturageC();
$ColisC = new ColisController();

try {
    $covoiturages = $covoiturageController->listCovoiturages();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}

$currentDate = date('Y-m-d');

if (!isset($_GET['id_colis'])) {
    die("Invalid Request");
}

$id_colis = $_GET['id_colis'];
$colis = null;

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_covoit'])) {
        $id_covoit = $_POST['id_covoit'];

        $ColisC->updateColis(
            $colis['id_colis'],
            $colis['id_client'],
            $id_covoit,
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

        header("Location: ../colis/ColisList.php?success=1");
        exit();
    }
}
?>

<div class="route-cards">
    <?php
    $matchingCovoiturages = [];

    foreach ($covoiturages as $covoiturage) {
        if (
            $covoiturage['date_depart'] == $colis['date_colis'] &&
            $covoiturage['lieu_depart'] == $colis['lieu_ram'] &&
            $covoiturage['lieu_arrivee'] == $colis['lieu_dest'] &&
            $covoiturage['accepte_colis'] == 1 &&
            $covoiturage['colis_complet'] == 0
        ) {
            $matchingCovoiturages[] = $covoiturage;
        }
    }
    ?>

    <?php if (count($covoiturages) === 0): ?>
        <div class="empty-table" style="text-align: center; margin-top: 30px;">
            <h4>Il n'y a actuellement aucun covoiturage enregistré.</h4>
            <p>Ajoutez un nouveau covoiturage pour commencer à livrer les colis.</p>
            <a href="addCovoiturage.php" class="btn btn-success">Créer un covoiturage</a>
        </div>

    <?php elseif (!empty($matchingCovoiturages)): ?>
        <div style="text-align: center; margin: 30px 0;">
  <p style="font-size: 1.25rem; color: #333333; font-weight: 500;">
    Explorez les covoiturages disponibles correspondant au trajet de votre colis.
  </p>
</div>
        <?php foreach ($matchingCovoiturages as $covoiturage): ?>
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

                <form method="POST" style="text-align: right; margin-top: 10px;">
                    <input type="hidden" name="id_covoit" value="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                    <input type="hidden" name="id_colis" value="<?= htmlspecialchars($id_colis) ?>">
                    <button type="submit" class="btn btn-primary">Sélectionner</button>
                </form>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!-- No exact match found, show suggestions -->
        <div class="no-results" style="text-align: center; margin-top: 30px;">
            <h4>Aucun covoiturage trouvé pour ce colis à cette date et trajet exact</h4>
            <br>
            <p>Voici quelques suggestions avec un départ proche et la même ville de départ</p>
        </div>

        <?php
        $suggestedCovoiturages = [];
        foreach ($covoiturages as $covoiturage) {
            $dateDiff = abs(strtotime($covoiturage['date_depart']) - strtotime($colis['date_colis']));
            $daysDiff = $dateDiff / (60 * 60 * 24);

            if (
                $covoiturage['lieu_depart'] == $colis['lieu_ram'] &&
                $daysDiff <= 3 &&
                $covoiturage['accepte_colis'] == 1 &&
                $covoiturage['colis_complet'] == 0
            ) {
                $suggestedCovoiturages[] = $covoiturage;
            }
        }

        if (!empty($suggestedCovoiturages)):
            foreach ($suggestedCovoiturages as $covoiturage): ?>
                <div class="route-card">
                    <h3>Trajet de <?= htmlspecialchars($covoiturage['lieu_depart']) ?> à <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></h3>
                    <p><strong>Date:</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                    <p><strong>Heure:</strong> <?= htmlspecialchars($covoiturage['temps_depart']) ?></p>
                    <p><strong>Places disponibles:</strong> <?= htmlspecialchars($covoiturage['places_dispo']) ?></p>
                    <p><strong>Prix:</strong> <?= htmlspecialchars($covoiturage['prix']) ?> TND</p>
                    <p><strong>Colis:</strong> <?= $covoiturage['accepte_colis'] == 1 ? 'Colis acceptés.' : 'Colis non acceptés.' ?></p>
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
            <?php endforeach;
        else: ?>
            <div style="text-align:center;">
                <p>Aucune suggestion disponible pour le moment.</p>
                <form method="GET" action="../colis/updateColis.php" style="display:inline;">
                    <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">
                    <button type="submit" class="btn btn-primary">Modifier Colis</button>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            this.querySelector('button[type="submit"]').disabled = true;
        });
    });
</script>

<script src="voirvehicule.js"></script>
