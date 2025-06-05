<?php
require_once 'Controller/userC.php';
header('Content-Type: application/json');
$userController = new UserC();
$users = $userController->getAllUsersWithDescriptors(); // Implement this method to return id, nom, prenom, face_descriptor
echo json_encode(array_map(function($u) {
    return [
        'id' => $u['id'],
        'nom' => $u['nom'],
        'prenom' => $u['prenom'],
        'descriptor' => $u['face_descriptor'] ? json_decode($u['face_descriptor']) : null
    ];
}, $users));
?>