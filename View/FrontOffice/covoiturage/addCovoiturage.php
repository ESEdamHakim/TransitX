<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php'; // Corrected path
require_once __DIR__ . '/../../../Model/Covoiturage.php';      // Corrected path

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
   // Convert "oui" and "non" to 1 and 0
   $accepteColis = ($accepteColis === 'oui') ? 1 : 0;
   $colisComplet = ($colisComplet === 'oui') ? 1 : 0;

    // Validate required fields
    if (!$dateDepart || !$placesDispo || !$lieuDepart || !$lieuArrivee || !$accepteColis || !$colisComplet || !$tempsDepart || !$prix) {
        echo "Erreur : Tous les champs sont obligatoires.";
        exit;
    }

    // Validate the length of the details field
    if (!empty($details) && strlen($details) > 100) {
        echo "Erreur : Le champ 'Détails supplémentaires' ne peut pas dépasser 100 caractères.";
        exit;
    }

    // If details is empty, set it to null
    if (empty($details)) {
        $details = null;
    }

    // Validate the number of available seats
    if (!$placesDispo || $placesDispo <= 0) {
        echo "Erreur : Le nombre de places disponibles est invalide.";
        exit;
    }

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