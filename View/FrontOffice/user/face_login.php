<?php
require_once '../../../Controller/UserC.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? null;

if ($userId) {
    $_SESSION['user_id'] = $userId;

    // Fetch user type from DB
    $userController = new UserC();
    $user = $userController->showUser($userId); // Adjust this to your method

    if ($user) {
        $_SESSION['user_type'] = $user->getType(); // Or whatever method/field gives the type
    }
    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['success' => false]);
exit;
?>