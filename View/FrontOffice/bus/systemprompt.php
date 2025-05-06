<?php
require_once '../../../Controller/trajetcontroller.php';
require_once '../../../Controller/buscontroller.php';

header('Content-Type: application/json');

$trajetController = new TrajetController();
$busController = new BusController();

$trajets = $trajetController->listTrajets(); // Should return an array
$buses = $busController->listBuses();        // Should return an array

echo json_encode([
    'trajets' => $trajets,
    'buses' => $buses
]);
