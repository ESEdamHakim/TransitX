<?php

require_once __DIR__ . '/../../../Controller/vehiculeC.php';

require_once __DIR__ . '/../../../appConfig.php';
header('Content-Type: application/json'); // Ensure the response is JSON

// Check if the user is logged in
if (!isset($id_user)) {
    echo "Erreur : Utilisateur non connecté.";
    exit;
}


// Check if the request is POST and id_vehicule is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_vehicule']) && !empty($_POST['id_vehicule'])) {
    $id_vehicule = intval($_POST['id_vehicule']); // Sanitize the input

    $vehiculeController = new VehiculeC();

    try {
        // Attempt to delete the vehicle
        $deletionSuccessful = $vehiculeController->deleteVehicule($id_vehicule, $id_user, false);

        if ($deletionSuccessful) {
            echo json_encode(['success' => true, 'message' => 'Véhicule supprimé avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur : Impossible de supprimer le véhicule.']);
        }
        exit;
    } catch (Exception $e) {
        // Handle exceptions and return an error response
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        exit;
    }
} else {
    // Invalid request
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    exit;
}
?>