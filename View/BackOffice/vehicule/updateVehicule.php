<?php
// Include necessary files

require_once '../../../Controller/vehiculeC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the data from the POST request
    $id_vehicule = $_POST['id_vehicule'];
    $matricule = $_POST['matricule'];
    $type_vehicule = $_POST['type_vehicule'];
    $nb_places = $_POST['nb_places'];
    $couleur = $_POST['couleur'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $confort = $_POST['confort'];
    $photo_vehicule = $_POST['photo_vehicule'];

    // Create a new Vehicule object
    $vehicule = new Vehicule();
    $vehicule->setIdVehicule($id_vehicule);
    $vehicule->setMatricule($matricule);
    $vehicule->setTypeVehicule($type_vehicule);
    $vehicule->setNbPlaces($nb_places);
    $vehicule->setCouleur($couleur);
    $vehicule->setMarque($marque);
    $vehicule->setModele($modele);
    $vehicule->setConfort($confort);
    $vehicule->setPhotoVehicule($photo_vehicule);

    // Use the VehiculeC class to update the vehicle
    $vehiculeController = new VehiculeC();

    try {
        $vehiculeController->updateVehicule($vehicule, null, true);
        echo json_encode(['success' => true, 'message' => 'Véhicule mis à jour avec succès.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>