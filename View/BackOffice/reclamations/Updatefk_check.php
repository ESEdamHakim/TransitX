<?php
include("../../../Controller/ReclamationController.php");

header('Content-Type: application/json');

// Get the id_covoit from the GET parameters
$id_covoit = isset($_GET['id_covoit']) ? intval($_GET['id_covoit']) : null;

$controller = new ReclamationController();

// Default response
$response = [
    'exists' => false,
    'errorMessage' => ''
];

// Check if the id_covoit is provided
if ($id_covoit !== null) {
    // Call the function to check if the covoiturage ID exists
    $response['exists'] = $controller->covoiturageExists($id_covoit);
    if (!$response['exists']) {
        // If it doesn't exist, set the error message
        $response['errorMessage'] = "Covoiturage avec l'ID $id_covoit n'existe pas.";
    }
}

// Return the response as JSON
echo json_encode($response);
?>
