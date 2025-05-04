<?php
require_once __DIR__ . '/../../../Controller/covoiturageC.php';

if (isset($_GET['action']) && $_GET['action'] === 'getUserById' && isset($_GET['id_user'])) {
    $controller = new CovoiturageC();
    try {
        $user = $controller->getUserById($_GET['id_user']);
        header('Content-Type: application/json');
        echo json_encode($user);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
} else {
    echo "Invalid request.";
}
?>