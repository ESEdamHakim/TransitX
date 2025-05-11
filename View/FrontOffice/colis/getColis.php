<?php
require_once __DIR__ . '/../../../Controller/ColisController.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

$ColisC = new ColisController();
$allColis = $ColisC->listColis();
$colisByCovoit = $ColisC->getColisByCovoiturage($userId);

// Filter list to only include parcels for the logged-in user
$filteredColis = array_filter($allColis, function ($colis) use ($userId) {
    return $colis['id_client'] == $userId;
});

echo json_encode([
    'list' => array_values($filteredColis),       // reindex for clean JSON
    'listByCovoit' => array_values($colisByCovoit)
]);
?>
