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

        $selectedCovoiturage = null;
        foreach ($covoiturages as $cov) {
            if ($cov['id_covoit'] == $id_covoit) {
                $selectedCovoiturage = $cov;
                break;
            }
        }

        if ($selectedCovoiturage) {
            $isExactMatch = (
                $selectedCovoiturage['date_depart'] == $colis['date_colis'] &&
                $selectedCovoiturage['lieu_depart'] == $colis['lieu_ram']
            );

            $newDateColis = $isExactMatch ? $colis['date_colis'] : $selectedCovoiturage['date_depart'];
            $newLieuRam = $isExactMatch ? $colis['lieu_ram'] : $selectedCovoiturage['lieu_depart'];
            $newLieuDest = $isExactMatch ? $colis['lieu_dest'] : $selectedCovoiturage['lieu_arrivee'];


            $ColisC->updateColis(
                $colis['id_colis'],
                $colis['id_client'],
                $id_covoit,
                $colis['statut'],
                $newDateColis,
                $colis['longueur'],
                $colis['largeur'],
                $colis['hauteur'],
                $colis['poids'],
                $newLieuRam,
                $newLieuDest,
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
}

?>
<style>
/* Parcels Table Container */
.parcels-table-container {
  margin-bottom: 1.5rem;
  overflow-x: auto;
}

/* Parcels Table */
.parcels-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 900px; /* Optional: improves horizontal layout on small screens */
}

.parcels-table th,
.parcels-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--gray-light);
}

.parcels-table th {
  font-weight: 600;
  color: var(--secondary-1); /* e.g., your #1f4f65 */
  background-color: var(--gray-light); /* e.g., your #f8f9fa */
}

.parcels-table tr:hover {
  background-color: #f1f1f1;
}

.parcels-table td button.btn,
.parcels-table td form button {
  padding: 6px 12px;
  font-size: 0.875rem;
  cursor: pointer;
}

.parcels-table td .btn-outline {
  background: none;
  color: var(--secondary-1);
  border: 1px solid var(--secondary-1);
}

.parcels-table td .btn-primary:hover {
  background-color: #84b494;
}

.parcels-table td .badge-secondary {
  background-color: #ccc;
  color: #333;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.85rem;
}

/* Optional: Set specific column width */
.parcels-table th:nth-child(3),
.parcels-table td:nth-child(3) {
  width: 130px;
}
</style>

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
<div class="main-container">
<?php if (count($covoiturages) === 0): ?>
    <div class="empty-table" style="text-align: center; margin-top: 30px;">
        <h4>Il n'y a actuellement aucun covoiturage enregistré.</h4>
        <p>Ajoutez un nouveau covoiturage pour commencer à livrer les colis.</p>
        <a href="addCovoiturage.php" class="btn btn-primary">Créer un covoiturage</a>
    </div>

<?php elseif (!empty($matchingCovoiturages)): ?>
    <div style="text-align: center; margin: 30px 0;">
        <p style="font-size: 1.25rem; color: #333333; font-weight: 500;">
            Explorez les covoiturages disponibles correspondant au trajet de votre colis.
        </p>
    </div>
    <div class="view-container table-view active">
        <div class="colis-table-container">
            <table class="parcels-table">
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Places</th>
                        <th>Prix (TND)</th>
                        <th>Colis</th>
                        <th>Détails</th>
                        <th>Véhicule</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matchingCovoiturages as $covoiturage): ?>
                        <tr>
                            <td><?= htmlspecialchars($covoiturage['lieu_depart']) ?></td>
                            <td><?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></td>
                            <td><?= htmlspecialchars($covoiturage['date_depart']) ?></td>
                            <td><?= htmlspecialchars($covoiturage['temps_depart']) ?></td>
                            <td><?= htmlspecialchars($covoiturage['places_dispo']) ?></td>
                            <td><?= htmlspecialchars($covoiturage['prix']) ?></td>
                            <td><?= $covoiturage['accepte_colis'] == 1 ? 'Acceptés' : 'Non acceptés' ?></td>
                            <td><?= htmlspecialchars($covoiturage['details'] ?? 'Aucun') ?></td>
                            <td>
                                <?php if (!empty($covoiturage['id_vehicule'])): ?>
                                    <button class="btn btn-outline" type="button"
                                        data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                                        Voir
                                    </button>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Indisponible</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id_covoit"
                                        value="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                                    <input type="hidden" name="id_colis" value="<?= htmlspecialchars($id_colis) ?>">
                                    <button type="submit" class="btn btn-primary">Sélectionner</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div style="text-align: center; margin: 30px 0;">
        <h4>Aucun covoiturage trouvé pour ce colis à cette date et trajet exact.</h4>
        <p style="font-size: 1.1rem; color: #555;">Voici quelques suggestions avec un départ proche et la même ville de départ</p>
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
    ?>

    <?php if (!empty($suggestedCovoiturages)): ?>
        <div class="view-container table-view active">
            <div class="colis-table-container">
                <table class="parcels-table">
                    <thead>
                        <tr>
                            <th>Départ</th>
                            <th>Arrivée</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Places</th>
                            <th>Prix (TND)</th>
                            <th>Colis</th>
                            <th>Détails</th>
                            <th>Véhicule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suggestedCovoiturages as $covoiturage): ?>
                            <tr>
                                <td><?= htmlspecialchars($covoiturage['lieu_depart']) ?></td>
                                <td><?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></td>
                                <td><?= htmlspecialchars($covoiturage['date_depart']) ?></td>
                                <td><?= htmlspecialchars($covoiturage['temps_depart']) ?></td>
                                <td><?= htmlspecialchars($covoiturage['places_dispo']) ?></td>
                                <td><?= htmlspecialchars($covoiturage['prix']) ?></td>
                                <td><?= $covoiturage['accepte_colis'] == 1 ? 'Acceptés' : 'Non acceptés' ?></td>
                                <td><?= htmlspecialchars($covoiturage['details'] ?? 'Aucun') ?></td>
                                <td>
                                    <?php if (!empty($covoiturage['id_vehicule'])): ?>
                                        <button class="btn btn-outline" type="button"
                                            data-id-covoiturage="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                                            Voir
                                        </button>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Indisponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="id_covoit" value="<?= htmlspecialchars($covoiturage['id_covoit']) ?>">
                                        <input type="hidden" name="id_colis" value="<?= htmlspecialchars($id_colis) ?>">
                                        <button type="submit" class="btn btn-primary">Sélectionner</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div style="text-align: center; margin-top: 30px;">
            <p>Aucune suggestion disponible pour le moment.</p>
            <form method="GET" action="../colis/updateColis.php" style="display: inline;">
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
