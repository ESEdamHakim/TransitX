<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../Controller/ColisController.php';

$covoiturageController = new CovoiturageC();
$ColisC = new ColisController();
session_start();

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
            $newLieuDest = $selectedCovoiturage['lieu_arrivee'];


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
            $covoiturage = $ColisC->getCovoiturageById($id_covoit);
            $id_receiver = $covoiturage['id_user'];

            $ColisC->addNotification($id_colis, $_SESSION['user_id'], $id_receiver);

            header("Location: crud.php?success=1");
            exit();
        }
    }
}

?>
<style>
    /* Container System */
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        box-sizing: border-box;
    }

    /* Page Header */
    .page-header {
        background-color: var(--white);
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--gray-medium);
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--secondary);
        margin: 0;
    }

    .page-header .breadcrumb {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0.5rem 0 0;
        font-size: 0.875rem;
    }

    .page-header .breadcrumb li:not(:last-child)::after {
        content: "/";
        margin: 0 0.5rem;
        color: var(--gray-dark);
    }

    /* Card styles */
    .card {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        background-color: var(--white);
        border-bottom: 1px solid var(--gray-medium);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        color: var(--secondary);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Alert styles */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        border-left: 4px solid transparent;
    }

    .alert-info {
        background-color: var(--secondary-light);
        border-left-color: var(--secondary);
        color: var(--secondary);
    }

    .alert-warning {
        background-color: #fff8e1;
        border-left-color: var(--warning);
        color: #856404;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-left-color: var(--danger);
        color: #721c24;
    }

    /* Badge styles */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .badge-secondary {
        background-color: var(--gray-medium);
        color: var(--text-dark);
    }

    .badge-success {
        background-color: var(--success);
        color: white;
    }

    /* Table styles */
    .table-container {
        width: 100%;
        overflow-x: auto;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
    }

    .parcels-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 900px;
    }

    .parcels-table th,
    .parcels-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--gray-medium);
    }

    .parcels-table th {
        font-weight: 600;
        color: var(--secondary);
        background-color: var(--gray-light);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .parcels-table th:first-child {
        border-top-left-radius: var(--border-radius);
    }

    .parcels-table th:last-child {
        border-top-right-radius: var(--border-radius);
    }

    .parcels-table tr:last-child td:first-child {
        border-bottom-left-radius: var(--border-radius);
    }

    .parcels-table tr:last-child td:last-child {
        border-bottom-right-radius: var(--border-radius);
    }

    .parcels-table tr:hover {
        background-color: var(--primary-light);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--text-light);
        margin-bottom: 1.5rem;
    }

    /* Section title */
    .section-title {
        font-size: 1.5rem;
        color: var(--secondary);
        margin: 2rem 0 1rem;
        text-align: center;
        font-weight: 600;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--primary);
    }

    .info-text {
        font-size: 1rem;
        color: var(--text-light);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-header h2 {
            margin-bottom: 0.5rem;
        }

        .btn {
            padding: 0.375rem 0.75rem;
        }
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
        $covoiturage['colis_complet'] == 0 && $covoiturage['id_user'] != $_SESSION['user_id']
    ) {
        $matchingCovoiturages[] = $covoiturage;
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>Correspondance Covoiturage</h1>
    </div>

    <?php if (count($covoiturages) === 0): ?>
        <div class="card alert alert-info">
            <h4>Il n'y a actuellement aucun covoiturage enregistré.</h4>
            <p>Ajoutez un nouveau covoiturage pour commencer à livrer les colis.</p>
            <a href="addCovoiturage.php" class="btn btn-primary">Créer un covoiturage</a>
        </div>

    <?php elseif (!empty($matchingCovoiturages)): ?>
        <div class="card">
            <div class="card-header">
                <h2>Covoiturages Correspondants</h2>
            </div>
            <div class="card-body table-container">
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
                                <td><?= $covoiturage['accepte_colis'] ? 'Acceptés' : 'Non acceptés' ?></td>
                                <td><?= htmlspecialchars($covoiturage['details'] ?? 'Aucun') ?></td>
                                <td>
                                    <?php if (!empty($covoiturage['id_vehicule'])): ?>
                                        <button class="btn btn-outline" type="button"
                                            data-id-covoiturage="<?= $covoiturage['id_covoit'] ?>">Voir</button>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Indisponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="id_covoit" value="<?= $covoiturage['id_covoit'] ?>">
                                        <input type="hidden" name="id_colis" value="<?= $id_colis ?>">
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
        <div class="card alert alert-warning">
            <h4>Aucun covoiturage trouvé pour ce colis à cette date et trajet exact.</h4>
            <p>Voici quelques suggestions avec un départ proche et la même ville de départ :</p>
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
                $covoiturage['colis_complet'] == 0 && $covoiturage['id_user'] != $_SESSION['user_id']
            ) {
                $suggestedCovoiturages[] = $covoiturage;
            }
        }
        ?>

        <?php if (!empty($suggestedCovoiturages)): ?>
            <div class="card">
                <div class="card-header">
                    <h2>Suggestions proches</h2>
                </div>
                <div class="card-body table-container">
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
                                    <td><?= $covoiturage['accepte_colis'] ? 'Acceptés' : 'Non acceptés' ?></td>
                                    <td><?= htmlspecialchars($covoiturage['details'] ?? 'Aucun') ?></td>
                                    <td>
                                        <?php if (!empty($covoiturage['id_vehicule'])): ?>
                                            <button class="btn btn-outline" type="button"
                                                data-id-covoiturage="<?= $covoiturage['id_covoit'] ?>">Voir</button>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Indisponible</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="id_covoit" value="<?= $covoiturage['id_covoit'] ?>">
                                            <input type="hidden" name="id_colis" value="<?= $id_colis ?>">
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
            <div class="card alert alert-danger">
                <p>Aucune suggestion disponible pour le moment.</p>
                <br>
                <form method="GET" action="../colis/updateColis.php">
                    <input type="hidden" name="id_colis" value="<?= $colis['id_colis'] ?>">
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