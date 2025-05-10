<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/appConfig.php';
header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $covoiturageId = $data['covoiturageId'] ?? null;
    $id_user = $data['id_user'] ?? null;
    $action = $data['action'] ?? null;
    
    if (!$covoiturageId || !$id_user || !in_array($action, ['accept', 'reject'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        exit;
    }

    try {
        $db = config::getConnexion();

        // Check if the booking exists
        $checkQuery = $db->prepare("SELECT * FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
        $checkQuery->execute([
            ':id_covoiturage' => $covoiturageId,
            ':id_user' => $id_user
        ]);


        if ($checkQuery->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Aucune réservation trouvée à mettre à jour.']);
            exit;
        }

        // Update the booking status
        $status = $action === 'accept' ? 'accepted' : 'rejected';
        $updateQuery = $db->prepare("UPDATE bookings SET notification_status = :status WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
        $updateQuery->execute([
            ':status' => $status,
            ':id_covoiturage' => $covoiturageId,
            ':id_user' => $id_user
        ]);

        // Add a notification for the requester
        $message = $action === 'accept' ? 'Votre demande a été acceptée.' : 'Votre demande a été refusée.';
        $notificationQuery = $db->prepare("INSERT INTO notifications (id_user, id_covoiturage, message, is_read) VALUES (:id_user, :id_covoiturage, :message, 0)");
        $notificationQuery->execute([
            ':id_user' => $id_user,
            ':id_covoiturage' => $covoiturageId,
            ':message' => $message
        ]);

        echo json_encode(['success' => true, 'message' => 'Le statut de la réservation a été mis à jour avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}