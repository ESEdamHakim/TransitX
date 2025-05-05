<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/config.php'; // Includes session_start()
header('Content-Type: application/json');

// Start the session to ensure session variables are accessible
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $covoiturageId = $data['covoiturageId'] ?? null;
    $action = $data['action'] ?? null;

    // Debugging: Check if the session variable is set
    if (!isset($_SESSION['id_user'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Session user ID is not set.',
            'debug' => [
                'covoiturageId' => $covoiturageId,
                'userId' => null,
                'action' => $action,
                'session' => $_SESSION
            ]
        ]);
        exit;
    }

    // Use the hardcoded user ID from the session
    $userId = $_SESSION['id_user'];

    // Debugging: Return the variables in the JSON response
    if (!$covoiturageId || !$userId || !in_array($action, ['book', 'cancel'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid data provided.',
            'debug' => [
                'covoiturageId' => $covoiturageId,
                'userId' => $userId,
                'action' => $action
            ]
        ]);
        exit;
    }

    try {
        // Use the existing database connection
        $db = config::getConnexion();

        if ($action === 'book') {
            // Check if the booking already exists
            $checkSql = "SELECT COUNT(*) FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user";
            $checkQuery = $db->prepare($checkSql);
            $checkQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);
            $exists = $checkQuery->fetchColumn();

            if ($exists) {
                echo json_encode(['success' => false, 'message' => 'You have already sent a booking request for this covoiturage.']);
                exit;
            }

            // Insert a new booking request into the database
            $sql = "INSERT INTO bookings (id_covoiturage, id_user, notification_status) 
                    VALUES (:id_covoiturage, :id_user, 'pending')";
            $query = $db->prepare($sql);
            $query->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);
            echo json_encode(['success' => true, 'message' => 'Booking request sent.']);
        } else {
            // Delete the booking request from the database
            $sql = "DELETE FROM bookings 
                    WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user";
            $query = $db->prepare($sql);
            $query->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $userId
            ]);
            echo json_encode(['success' => true, 'message' => 'Booking request canceled.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}