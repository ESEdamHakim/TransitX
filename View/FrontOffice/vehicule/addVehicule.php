<?php
require_once __DIR__ . '/../../../Controller/vehiculeC.php'; // Corrected path
require_once __DIR__ . '/../../../Model/vehicule.php';      // Corrected path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $matricule = filter_input(INPUT_POST, 'matricule', FILTER_SANITIZE_STRING);
    $typeVehicule = filter_input(INPUT_POST, 'type_vehicule', FILTER_SANITIZE_STRING);
    $nbPlaces = filter_input(INPUT_POST, 'nb_places', FILTER_SANITIZE_NUMBER_INT);
    $couleur = filter_input(INPUT_POST, 'couleur', FILTER_SANITIZE_STRING);
    $marque = filter_input(INPUT_POST, 'marque', FILTER_SANITIZE_STRING);
    $modele = filter_input(INPUT_POST, 'modele', FILTER_SANITIZE_STRING);
    $confort = filter_input(INPUT_POST, 'confort', FILTER_SANITIZE_STRING);
    $photoVehicule = $_FILES['photo_vehicule']['name'];
    
     // Debugging: Echo each attribute
     echo "Type de Véhicule: " . ($typeVehicule ?: "Non soumis") . "<br>";
     echo "Matricule: " . ($matricule ?: "Non soumis") . "<br>";
     echo "Nombre de Places: " . ($nbPlaces ?: "Non soumis") . "<br>";
     echo "Couleur: " . ($couleur ?: "Non soumis") . "<br>";
     echo "Marque: " . ($marque ?: "Non soumis") . "<br>";
     echo "Modèle: " . ($modele ?: "Non soumis") . "<br>";
     echo "Confort: " . ($confort ?: "Non soumis") . "<br>";
     echo "Photo du Véhicule: " . ($photoVehicule ?: "Non soumis") . "<br>";
    // Validate required fields
    if (empty($matricule) || empty($typeVehicule) || empty($nbPlaces) || empty($couleur) || empty($marque) || empty($modele) || empty($confort)) {
        echo "Erreur : Tous les champs sont obligatoires.";
        exit;
    }

    // Handle file upload
    if (isset($_FILES['photo_vehicule']) && $_FILES['photo_vehicule']['error'] === UPLOAD_ERR_OK) {

        $photoTmpPath = $_FILES['photo_vehicule']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['photo_vehicule']['name']);
        $uploadDir = __DIR__ . '/../../../uploads/';

        if (move_uploaded_file($photoTmpPath, $uploadDir . $photoName)) {
            echo "Photo uploaded successfully: " . $photoName;
        } else {
            echo "Erreur : Échec du téléchargement de la photo.";
            exit;
        }
    } else {
        $photoName = null; // No photo uploaded
    }

    // Hardcoded user ID for testing
    $id_user = 1;

    // Create a new Vehicule object
    $vehicule = new Vehicule(
        $matricule,
        $typeVehicule,
        $nbPlaces,
        $couleur,
        $marque,
        $modele,
        $confort,
        $photoName,
        $id_user
    );

    // Call the controller to add the vehicule
    $vehiculeController = new VehiculeC();
    $result = $vehiculeController->addVehicule($vehicule);

    // Redirect or display success message
    if ($result === "Véhicule ajouté avec succès.") {
        header('Location: index.php?success=1'); // Redirect to the index page with a success message
        exit;
    } else {
        echo $result; // Display the error message
    }
}

// Debugging: Log the submitted data
var_dump($_POST);
file_put_contents('log.txt', print_r($_POST, true));
exit;
?>