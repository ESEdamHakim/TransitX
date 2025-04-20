<?php

require_once '../../../Controller/CovoiturageC.php';

if (isset($_POST['id_covoit'])) {
    $id_covoit = $_POST['id_covoit'];
    $covoiturageController = new CovoiturageC();
    try {
        $covoiturageController->deleteCovoiturage($id_covoit, null, true);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID non fourni']);
}