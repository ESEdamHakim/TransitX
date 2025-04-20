<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_covoit'])) {
    $id_covoit = $_POST['id_covoit'];


    $id_user = 1;
    $covoiturageController = new CovoiturageC();

    try {
        // Call the deleteCovoiturage method to delete the covoiturage
        $covoiturageController->deleteCovoiturage($id_covoit, $id_user, false);

        // Return success response
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        // Return error response
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
} else {
    // Return error response for invalid request
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    exit;
}
?>