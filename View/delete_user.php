<?php
require_once __DIR__ . '/../Controller/userC.php'; 

$userController = new UserC();
$id = $_GET['id'] ?? null;

if ($id && filter_var($id, FILTER_VALIDATE_INT)) { // Validate ID input
    $userController->deleteUser($id);
}

header('Location: ../View/Backoffice/users/crud.php');
exit();
?>
