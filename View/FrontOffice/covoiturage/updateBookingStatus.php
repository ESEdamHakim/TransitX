<?php
require_once __DIR__ . '/../../../configuration/config.php'; // Include database connection
header('Content-Type: application/json');

// Start the session
session_start();

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

$covoiturageId = $data['covoiturageId'] ?? null;
$userId = $data['userId'] ?? null;
$action = $data['action'] ?? null;

if (!$covoiturageId || !$userId || !in_array($action, ['accept', 'reject'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

try {
    $db = config::getConnexion();

    if ($action === 'accept') {
        // Update the booking notification_status to accepted
        $sql = "UPDATE bookings SET notification_status = 'accepted' WHERE id_covoiturage = :covoiturageId AND id_user = :userId";
    } else {
        // Update the booking notification_status to rejected
        $sql = "UPDATE bookings SET notification_status = 'rejected' WHERE id_covoiturage = :covoiturageId AND id_user = :userId";
    }

    $query = $db->prepare($sql);
    $query->execute([
        ':covoiturageId' => $covoiturageId,
        ':userId' => $userId,
    ]);

    echo json_encode(['success' => true, 'message' => 'Booking notification status updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}