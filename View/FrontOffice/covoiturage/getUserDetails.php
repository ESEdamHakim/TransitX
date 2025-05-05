<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/appConfig.php';
header('Content-Type: application/json');

// Use the id_user from the GET request if provided, otherwise fallback to the session
$id_user = $_GET['id_user'] ?? $_SESSION['id_user'];

if ($id_user) {
    try {
        $covoiturageController = new CovoiturageC();
        $user = $covoiturageController->getUserById($id_user);

        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request. No user ID provided.']);
}