<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';

$id_user = 1;

$covoiturageController = new CovoiturageC();
try {
    $userCovoiturages = $covoiturageController->listUserCovoiturages($id_user);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>

<div class="user-route-cards">
    <h2>Vos Trajets Populaires</h2>
    <?php if (!empty($userCovoiturages)): ?>
        <?php foreach ($userCovoiturages as $covoiturage): ?>
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
                <div class="actions">
                    <button class="btn edit" data-id="<?= $covoiturage['id_covoit'] ?>">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                    <button class="btn delete" data-id="<?= $covoiturage['id_covoit'] ?>">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n'avez ajouté aucun trajet pour le moment.</p>
    <?php endif; ?>
</div>