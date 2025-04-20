<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../Model/Covoiturage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_covoit = $_POST['id_covoit'];
    $departure = $_POST['departure'];
    $destination = $_POST['destination'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $accept_parcels = $_POST['accept_parcels'] === 'oui' ? 1 : 0;
    $full_parcels = $_POST['full_parcels'] === 'oui' ? 1 : 0;
    $description = $_POST['description'];


    $id_user = 1;

    $covoiturageController = new CovoiturageC();

    try {
        // Create a new Covoiturage object
        $covoiturage = new Covoiturage(
            $date,
            $departure,
            $destination,
            $accept_parcels,
            $full_parcels,
            $description,
            $price,
            $time,
            $seats
        );

        // Call the updateCovoiturage method
        $covoiturageController->updateCovoiturage($covoiturage, $id_user, false);

        // Redirect to the main page with a success message
        header('Location: index.php?success=update');
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Erreur : Requête invalide.";
    exit;
}
?>