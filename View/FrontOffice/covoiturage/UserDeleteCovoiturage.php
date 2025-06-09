<?php

require_once __DIR__ . '/../../../Controller/CovoiturageC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_covoit'])) {
    $id_covoit = $_POST['id_covoit'];
    $covoiturageController = new CovoiturageC();
    try {
        $covoiturageController->deleteCovoiturage($id_covoit);
        header('Location: index.php?success=Trajet supprimé avec succès.');
        exit;
    } catch (Exception $e) {
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: index.php?error=Requête invalide.');
    exit;
}
?>