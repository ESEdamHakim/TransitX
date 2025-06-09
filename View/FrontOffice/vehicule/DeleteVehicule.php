<?php

require_once __DIR__ . '/../../../Controller/vehiculeC.php';
require_once __DIR__ . '/../../../appConfig.php';


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

        header('Location: index.php?success=Véhicule supprimé avec succès.');
        exit;
    } catch (Exception $e) {
        // Handle exceptions and return an error response
        header('Location: index.php?error=Erreur lors de la suppression du véhicule.');
        exit;
    }
} else {
    // Invalid request
    header('Location: index.php?error=Requête invalide.');
    exit;
}
?>