<?php
include("../../../Controller/ReclamationController.php");

header('Content-Type: application/json');

// Get the id_covoit and id_client from the GET parameters
$id_covoit = isset($_GET['id_covoit']) ? intval($_GET['id_covoit']) : null;
$id_client = isset($_GET['id_client']) ? intval($_GET['id_client']) : null;

$controller = new ReclamationController();

// Default response
$response = [
    'covoiturageExists' => true,
    'clientExists' => true,
    'covoiturageErrorMessage' => '',
    'clientErrorMessage' => ''
];

// Check if the id_covoit is provided and valid
if ($id_covoit !== null) {
    // Call the function to check if the covoiturage ID exists
    $covoiturageExists = $controller->covoiturageExists($id_covoit);
    if (!$covoiturageExists) {
        $response['covoiturageExists'] = false;
        $response['covoiturageErrorMessage'] = "Covoiturage avec l'ID $id_covoit n'existe pas.";
    }
}

// If id_client is provided, check for id_client as well
if ($id_client !== null) {
    $clientExists = $controller->clientExists($id_client);
    if (!$clientExists) {
        $response['clientExists'] = false;
        $response['clientErrorMessage'] = "Client avec l'ID $id_client n'existe pas.";
    }
}

// If neither exists, both checks failed, but otherwise we only show errors where necessary
echo json_encode($response);
?>
