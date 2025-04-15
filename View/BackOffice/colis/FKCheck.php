<?php
require_once __DIR__ . '../../../config.php';
require_once __DIR__ . '../../../Controller/ColisController.php'; // Adjust if needed

header('Content-Type: application/json');

$id_client = isset($_GET['id_client']) ? intval($_GET['id_client']) : null;
$id_covoit = isset($_GET['id_covoit']) ? $_GET['id_covoit'] : null; // Allow NULL

$controller = new ColisController();

$response = [
    'clientExists' => false,
    'covoitExists' => false,
    'errorMessage' => '' // Add error message
];

if ($id_client !== null) {
    $response['clientExists'] = $controller->clientExists($id_client);
    if (!$response['clientExists']) {
        $response['errorMessage'] = "Client with ID $id_client does not exist.";
    }
}

// Only check for covoiturage existence if id_covoit is not NULL
if ($id_covoit !== null) {
    $response['covoitExists'] = $controller->covoiturageExists($id_covoit);
    if (!$response['covoitExists']) {
        $response['errorMessage'] = "Covoiturage with ID $id_covoit does not exist.";
    }
}

echo json_encode($response);
?>
