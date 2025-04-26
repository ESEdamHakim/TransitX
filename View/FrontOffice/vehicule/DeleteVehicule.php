<?php
require_once __DIR__ . '/../../../Controller/vehiculeC.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_vehicule'])) {
    $id_vehicule = $_POST['id_vehicule'];
    //$id_user = 1; // Hardcoded user ID for testing
    require_once __DIR__ . '/../../../configuration/appConfig.php';
    $vehiculeController = new VehiculeC();

    try {
       
        $vehiculeController->deleteVehicule($id_vehicule, $id_user, false);
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {       
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    exit;
}
?>