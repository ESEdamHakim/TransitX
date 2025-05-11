<?php

require_once __DIR__ . '/../../../Controller/vehiculeC.php';
require_once __DIR__ . '/../../../Controller/covoiturageC.php';

header('Content-Type: application/json');

if (isset($_GET['id_covoiturage'])) {
    $id_covoiturage = filter_input(INPUT_GET, 'id_covoiturage', FILTER_SANITIZE_NUMBER_INT);

    if ($id_covoiturage) {
        $covoiturageController = new CovoiturageC();
        $vehiculeController = new VehiculeC();

        try {
            // Get the id_vehicule using the id_covoiturage
            $id_vehicule = $covoiturageController->getVehiculeIdByCovoiturageId($id_covoiturage);

            if ($id_vehicule) {
                // Fetch the vehicle details using the id_vehicule
                $vehicule = $vehiculeController->getVehiculeById($id_vehicule);

                if ($vehicule) {
                    // Add the directory path to the photo_vehicule field
                    $vehicule['photo_vehicule'] = !empty($vehicule['photo_vehicule'])
                        ? '../../assets/uploads/' . $vehicule['photo_vehicule']
                        : null;// Use null if no photo is available

                    echo json_encode(['success' => true, 'vehicule' => $vehicule]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Véhicule introuvable.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Aucun véhicule associé à ce covoiturage.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de covoiturage invalide.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de covoiturage manquant.']);
}
?>