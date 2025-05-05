<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/appConfig.php'; // Includes session_start()
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $covoiturageId = $data['covoiturageId'] ?? null;
    $action = $data['action'] ?? null;

    // Use the hardcoded user ID from the session
    $userId = $_SESSION['id_user'];

    if ($covoiturageId && $userId && in_array($action, ['book', 'cancel'])) {
        try {
            // Initialize session storage for booking requests if not already set
            if (!isset($_SESSION['booking_requests'])) {
                $_SESSION['booking_requests'] = [];
            }

            if ($action === 'book') {
                // Add the booking request to the session
                $_SESSION['booking_requests'][$covoiturageId] = $userId;
                echo json_encode(['success' => true, 'message' => 'Booking request sent.']);
            } else {
                // Remove the booking request from the session
                unset($_SESSION['booking_requests'][$covoiturageId]);
                echo json_encode(['success' => true, 'message' => 'Booking request canceled.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}