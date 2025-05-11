<?php
// Include necessary files
require_once '../../../Controller/vehiculeC.php';
require_once '../../../Model/vehicule.php';

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
    //$id_user = 1; // Hardcoded user ID for testing
    require_once __DIR__ . '/../../../appConfig.php';
    if (!isset($id_user)) {
        echo "Erreur : Utilisateur non connecté.";
        exit;
    }
    // Handle photo upload or retain the existing photo
    $photo_vehicule = null;
    if (isset($_FILES['photo_vehicule']) && $_FILES['photo_vehicule']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo_vehicule']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['photo_vehicule']['name']);
        $uploadDir = '../../../uploads/';
        if (move_uploaded_file($photoTmpPath, $uploadDir . $photoName)) {
            $photo_vehicule = $photoName;
        } else {
            echo "Erreur : Échec du téléchargement de la photo.";
            exit;
        }
    } else {
        // Retain the existing photo if no new photo is uploaded
        $vehiculeController = new VehiculeC();
        $existingVehicule = $vehiculeController->getVehiculeById($id_vehicule);
        $photo_vehicule = $existingVehicule['photo_vehicule'];
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
        $vehiculeController->updateVehicule($vehicule, $id_user, false);

        // Redirect to the display page with a success message
        header('Location: index.php?success=update');
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
} else {
    echo "Erreur : Requête invalide.";
    exit;
}
?>