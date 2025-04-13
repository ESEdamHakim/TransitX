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
?>

<div class="route-cards">
    <?php if (!empty($covoiturages)): ?>
        <?php foreach ($covoiturages as $covoiturage): ?>
            <div class="route-card">
                <h3>Trajet de <?= htmlspecialchars($covoiturage['lieu_depart']) ?> à
                    <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></h3>
                <p><strong>Date:</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                <p><strong>Heure:</strong> <?= htmlspecialchars($covoiturage['temps_depart']) ?></p>
                <p><strong>Places disponibles:</strong> <?= htmlspecialchars($covoiturage['places_dispo']) ?></p>
                <p><strong>Prix:</strong> <?= htmlspecialchars($covoiturage['prix']) ?> TND</p>
                <p><strong>Accepte les colis:</strong> <?= $covoiturage['accepte_colis'] ? 'Oui' : 'Non' ?></p>
                <p><strong>Colis complet:</strong> <?= $covoiturage['colis_complet'] ? 'Oui' : 'Non' ?></p>
                <p><strong>Détails:</strong> <?= htmlspecialchars($covoiturage['details'] ?? 'Aucun détail fourni') ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun trajet disponible pour le moment.</p>
    <?php endif; ?>
</div>