<?php

require_once '../../../Controller/CovoiturageC.php';

// Instantiate the controller
$covoiturageController = new CovoiturageC();

// Fetch the list of covoiturages
try {
    $covoiturages = $covoiturageController->listUserCovoiturage();
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>