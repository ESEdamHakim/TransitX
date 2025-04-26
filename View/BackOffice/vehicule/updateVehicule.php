<?php
// Include necessary files
require_once '../../../Controller/vehiculeC.php';
require_once '../../../Model/vehicule.php';

session_start(); // Start the session to access user data

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $id_vehicule = $_POST['id_vehicule'];
    $matricule = $_POST['matricule'];
    $type_vehicule = $_POST['type_vehicule'];
    $nb_places = $_POST['nb_places'];
    $couleur = $_POST['couleur'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $confort = $_POST['confort'];

    // Handle photo upload or retain the existing photo
    $photo_vehicule = null;
    if (isset($_FILES['photo_vehicule']) && $_FILES['photo_vehicule']['error'] === UPLOAD_ERR_OK) {
        // A new photo is uploaded
        $photoTmpPath = $_FILES['photo_vehicule']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['photo_vehicule']['name']);
        $uploadDir = '../../../uploads/';
        if (move_uploaded_file($photoTmpPath, $uploadDir . $photoName)) {
            $photo_vehicule = $photoName;
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur : Échec du téléchargement de la photo.']);
            exit;
        }
    } elseif (!empty($_POST['existing_photo'])) {
        // Use the existing photo if no new photo is uploaded
        $photo_vehicule = $_POST['existing_photo'];
    } else {
        // No photo provided (new or existing)
        echo json_encode(['success' => false, 'message' => 'Erreur : Aucune photo fournie.']);
        exit;
    }

    // Create a Vehicule object and set its properties
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

    // Update the vehicle using the VehiculeC controller
    $vehiculeController = new VehiculeC();

    try {
        // Pass null for id_user and true for isAdmin
        $vehiculeController->updateVehicule($vehicule, null, true);

        // Return a success response
        echo json_encode(['success' => true, 'message' => 'Véhicule mis à jour avec succès.']);
        exit;
    } catch (Exception $e) {
        // Return an error response
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    exit;
}
?>