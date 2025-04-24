<?php

require_once '../../../Controller/vehiculeC.php';

// Instantiate the controller
$vehiculeController = new VehiculeC();

// Fetch the list of vehicles
try {
    $vehicules = $vehiculeController->listVehicules();
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>