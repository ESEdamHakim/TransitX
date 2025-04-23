<?php
require_once __DIR__ . '../../../config.php';
require_once __DIR__ . '../../../Controller/ColisController.php';

header('Content-Type: application/json');

$id_client = isset($_GET['id_client']) ? intval($_GET['id_client']) : null;
$id_covoit = isset($_GET['id_covoit']) ? intval($_GET['id_covoit']) : null;

$controller = new ColisController();

$response = [
    'clientExists' => true,
    'covoitExists' => true,
    'errorMessage' => ''
];

if ($id_client !== null && !$controller->clientExists($id_client)) {
    $response['clientExists'] = false;
    $response['errorMessage'] = "Client avec l'ID $id_client n'existe pas.";
} elseif ($id_covoit !== null && !$controller->covoiturageExists($id_covoit)) {
    $response['covoitExists'] = false;
    $response['errorMessage'] = "Covoiturage avec l'ID $id_covoit n'existe pas.";
}

echo json_encode($response);
?>
