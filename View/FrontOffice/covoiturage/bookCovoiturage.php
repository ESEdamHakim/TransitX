<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/config.php'; // Includes session_start()
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $covoiturageId = $data['covoiturageId'] ?? null;
    $action = $data['action'] ?? null;

    if (!isset($_SESSION['id_user'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    $userId = $_SESSION['id_user'];

    if (!$covoiturageId || !$userId || !in_array($action, ['book', 'cancel'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        exit;
    }

    try {
        $db = config::getConnexion();

        if ($action === 'book') {
            // Check if a booking already exists
            $checkQuery = $db->prepare("SELECT * FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
            $checkQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);

            if ($checkQuery->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'You have already sent a booking request for this covoiturage.']);
                exit;
            }

            // Insert a new booking
            $insertQuery = $db->prepare("INSERT INTO bookings (id_covoiturage, id_user, notification_status) VALUES (:id_covoiturage, :id_user, 'pending')");
            $insertQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);

            echo json_encode(['success' => true, 'message' => 'Booking request sent successfully.']);
        } elseif ($action === 'cancel') {
            // Check if the booking exists before attempting to delete
            $checkQuery = $db->prepare("SELECT * FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
            $checkQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);

            if ($checkQuery->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'No booking found to cancel.']);
                exit;
            }

            // Delete the booking
            $deleteQuery = $db->prepare("DELETE FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
            $deleteQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);

            echo json_encode(['success' => true, 'message' => 'Booking request canceled successfully.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}