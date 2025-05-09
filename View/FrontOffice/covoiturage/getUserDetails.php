<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/appConfig.php';
header('Content-Type: application/json');

// Use the id_user from the GET request if provided, otherwise fallback to the session
//$id_user = $_GET['id'] ?? $_SESSION['id'];
// Ensure the user is logged in
if (!isset($id_user)) {
    echo json_encode([
        'success' => false,
        'message' => 'Utilisateur non connecté.'
    ]);
    exit;
}
if ($id_user) {
    try {
        $covoiturageController = new CovoiturageC();
        $user = $covoiturageController->getUserById($id_user);

        if ($user) {
            echo json_encode([
                'success' => true,
                'data' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request. No user ID provided.'
    ]);
}