<?php
require_once __DIR__ . '/../../../Controller/vehiculeC.php'; // Corrected path
require_once __DIR__ . '/../../../Model/vehicule.php';      // Corrected path
require_once __DIR__ . '/../../../appConfig.php';

if (!isset($id_user)) {
    echo "Erreur : Utilisateur non connecté.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $matricule = filter_input(INPUT_POST, 'matricule', FILTER_SANITIZE_STRING);
    $typeVehicule = filter_input(INPUT_POST, 'type_vehicule', FILTER_SANITIZE_STRING);
    $nbPlaces = filter_input(INPUT_POST, 'nb_places', FILTER_SANITIZE_NUMBER_INT);
    $couleur = filter_input(INPUT_POST, 'couleur', FILTER_SANITIZE_STRING);
    $marque = filter_input(INPUT_POST, 'marque', FILTER_SANITIZE_STRING);
    $modele = filter_input(INPUT_POST, 'modele', FILTER_SANITIZE_STRING);
    $confort = filter_input(INPUT_POST, 'confort_final', FILTER_SANITIZE_STRING);
    $customConfort = filter_input(INPUT_POST, 'custom_confort', FILTER_SANITIZE_STRING);

    if ($confort === 'other') {
        $confort = $customConfort ?: null;
    }

    $photoVehicule = $_FILES['photo_vehicule']['name'];

    // Handle file upload
    if (isset($_FILES['photo_vehicule']) && $_FILES['photo_vehicule']['error'] === UPLOAD_ERR_OK) {

        $photoTmpPath = $_FILES['photo_vehicule']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['photo_vehicule']['name']);
        $uploadDir = __DIR__ . '/../../assets/uploads/' . $photoName;

        move_uploaded_file($photoTmpPath, $uploadDir . $photoName);

    } else {
        $photoName = null; // No photo uploaded
    }


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