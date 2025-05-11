<?php

require_once '../../../Controller/vehiculeC.php';

if (isset($_POST['id_vehicule'])) {
    $id_vehicule = $_POST['id_vehicule'];
    $vehiculeController = new VehiculeC();
    try {
        $vehiculeController->deleteVehicule($id_vehicule, null, true);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID non fourni']);
}
?>