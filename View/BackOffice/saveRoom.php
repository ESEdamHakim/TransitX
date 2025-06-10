<?php
require_once '../../Controller/UserC.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['roomName']) && !empty($data['roomName'])) {
    try {
        $userC = new UserC();
        $userC->saveMeetingRoom($data['roomName']);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No room name']);
}