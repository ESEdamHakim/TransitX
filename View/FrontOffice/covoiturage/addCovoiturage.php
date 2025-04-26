<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php'; // Corrected path
require_once __DIR__ . '/../../../Model/Covoiturage.php';      // Corrected path
require_once __DIR__ . '/../../../Controller/vehiculeC.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $dateDepart = filter_input(INPUT_POST, 'date_depart', FILTER_SANITIZE_STRING);
    $lieuDepart = filter_input(INPUT_POST, 'lieu_depart', FILTER_SANITIZE_STRING);
    $lieuArrivee = filter_input(INPUT_POST, 'lieu_arrivee', FILTER_SANITIZE_STRING);
    $accepteColis = filter_input(INPUT_POST, 'accepte_colis', FILTER_SANITIZE_STRING);
    $colisComplet = filter_input(INPUT_POST, 'colis_complet', FILTER_SANITIZE_STRING);
    $details = filter_input(INPUT_POST, 'details', FILTER_SANITIZE_STRING);
    $prix = filter_input(INPUT_POST, 'prix', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tempsDepart = filter_input(INPUT_POST, 'temps_depart', FILTER_SANITIZE_STRING);
    $placesDispo = filter_input(INPUT_POST, 'places_dispo', FILTER_SANITIZE_NUMBER_INT);
    $matricule = filter_input(INPUT_POST, 'matricule', FILTER_SANITIZE_STRING);
    // Convert "oui" and "non" to 1 and 0
    if ($accepteColis === 'oui') {
        $accepteColis = 1;
    } elseif ($accepteColis === 'non') {
        $accepteColis = 0;
    } else {
        echo "Erreur : Veuillez indiquer si vous acceptez les colis.";
        exit;
    }

    if ($colisComplet === 'oui') {
        $colisComplet = 1;
    } elseif ($colisComplet === 'non') {
        $colisComplet = 0;
    } else {
        echo "Erreur : Veuillez indiquer si les colis sont complets.";
        exit;
    }




    $id_user = 1;

    // Validate the matricule and fetch the corresponding vehicle
    $vehiculeController = new VehiculeC();
    $vehicule = $vehiculeController->getVehiculeByMatriculeAndUser($matricule, $id_user);

    if (!$vehicule) {
        echo "Erreur : Le matricule saisi n'existe pas ou n'appartient pas à l'utilisateur.";
        exit;
    }

    // Get the id_vehicule from the fetched vehicle
    $id_vehicule = $vehicule['id_vehicule'];

    // Validate the number of available seats
    if (!$placesDispo || $placesDispo <= 0) {
        echo "Erreur : Le nombre de places disponibles est invalide.";
        exit;
    }
    // Hardcoded user ID for testing
    $id_user = 1;
    // Create a new Covoiturage object
    $covoiturage = new Covoiturage(
        $dateDepart,
        $lieuDepart,
        $lieuArrivee,
        $accepteColis,
        $colisComplet,
        $details,
        $prix,
        $tempsDepart,
        $placesDispo
    );
    $covoiturage->setIdUser($id_user); 
    $covoiturage->setIdVehicule($id_vehicule);
    // Call the controller to add the covoiturage
    $covoiturageController = new CovoiturageC();
    $result = $covoiturageController->addCovoiturage($covoiturage);

    // Redirect or display success message
    if ($result === "Trajet ajouté avec succès.") {
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