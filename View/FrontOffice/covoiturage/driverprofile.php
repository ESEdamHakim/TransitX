<?php
require_once __DIR__ . '/../../../appConfig.php';
require_once __DIR__ . '/../../../Controller/covoiturageC.php';
// Ensure the user is logged in
if (!isset($id_user)) {
    echo "Erreur : Utilisateur non connecté.";
    exit;
}
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