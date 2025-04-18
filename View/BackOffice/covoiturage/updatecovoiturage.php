<?php
// Include necessary files

require_once '../../../Controller/CovoiturageC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the data from the POST request
    $id_covoit = $_POST['id_covoit'];
    $lieu_depart = $_POST['departure'];
    $lieu_arrivee = $_POST['destination'];
    $date_depart = $_POST['date'];
    $temps_depart = $_POST['time'];
    $places_dispo = $_POST['seats'];
    $prix = $_POST['price'];
    $accepte_colis = $_POST['accept_parcels'];
    $colis_complet = $_POST['full_parcels'];
    $details = $_POST['description'];

    // Create a new Covoiturage object
    $covoit = new Covoiturage();
    $covoit->setIdCovoit($id_covoit);
    $covoit->setLieuDepart($lieu_depart);
    $covoit->setLieuArrivee($lieu_arrivee);
    $covoit->setDateDepart($date_depart);
    $covoit->setTempsDepart($temps_depart);
    $covoit->setPlacesDispo($places_dispo);
    $covoit->setPrix($prix);
    $covoit->setAccepteColis($accepte_colis);
    $covoit->setColisComplet($colis_complet);
    $covoit->setDetails($details);

    // Use the CovoiturageC class to update the covoiturage
    $covoiturageC = new CovoiturageC();

    try {
        $covoiturageC->updateCovoiturage($covoit);
        echo "Trajet mis à jour avec succès.";
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>