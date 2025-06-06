<?php
// Include necessary files
require_once '../../../Controller/CovoiturageC.php';
require_once '../../../Model/Covoiturage.php';
require_once __DIR__ . '/../../../appConfig.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure the user is logged in
if (!isset($id_user)) {
    echo "Erreur : Utilisateur non connecté.";
    exit;
}
    $id_covoit = $_POST['id_covoit'];
    $lieu_depart = $_POST['departure'];
    $lieu_arrivee = $_POST['destination'];
    $date_depart = $_POST['date'];
    $temps_depart = $_POST['time'];
    $places_dispo = $_POST['seats'];
    $prix = $_POST['price'];
    $accepte_colis = $_POST['accept_parcels'] === 'oui' ? 1 : 0;
    $colis_complet = $_POST['full_parcels'] === 'oui' ? 1 : 0;
    $details = $_POST['description'];
    $id_vehicule = $_POST['id_vehicule'];
    
    
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
    $covoit->setIdVehicule($id_vehicule);
    
    $covoiturageC = new CovoiturageC();

    try {
        
        $covoiturageC->updateCovoiturage($covoit, $id_user, false);

        
        header('Location: index.php?success=update');
        exit;
    } catch (Exception $e) {
        // Display a sanitized error message
        echo "Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage());
        exit;
    }
} else {
    echo "Erreur : Requête invalide.";
    exit;
}
?>