<?php

require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/config.php'; // Includes session_start()
require_once __DIR__ . '/mailcovoit.php'; // Include the mail logic
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $covoiturageId = $data['covoiturageId'] ?? null;
    $action = $data['action'] ?? null;

    if (!isset($id_user)) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    if (!$covoiturageId || !$id_user || !in_array($action, ['book', 'cancel'])) {
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
                ':id_user' => $id_user
            ]);

            if ($checkQuery->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'Vous avez déjà envoyé une demande pour ce covoiturage.']);
                exit;
            }

            // Insert a new booking
            $insertQuery = $db->prepare("INSERT INTO bookings (id_covoiturage, id_user, notification_status) VALUES (:id_covoiturage, :id_user, 'pending')");
            $insertQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $id_user
            ]);

            // Fetch the email of the covoiturage creator
            $creatorQuery = $db->prepare("SELECT u.email FROM user u JOIN covoiturages c ON u.id = c.id_user WHERE c.id_covoit = :id_covoiturage");
            $creatorQuery->execute([':id_covoiturage' => $covoiturageId]);
            $creatorEmail = $creatorQuery->fetchColumn();

            if ($creatorEmail) {
                // Send an email to the creator
                $subject = "Nouvelle demande de réservation pour votre covoiturage";
                $body = "Bonjour,<br><br>Un utilisateur a envoyé une demande pour rejoindre votre covoiturage.<br><br>Connectez-vous à votre compte pour voir les détails.";
                $mailSent = sendMail($creatorEmail, $subject, $body);

                if (!$mailSent) {
                    error_log("Failed to send email to $creatorEmail");
                }
            }

            echo json_encode(['success' => true, 'message' => 'Votre demande de réservation a été envoyée avec succès.']);
        } elseif ($action === 'cancel') {
            // Check if the booking exists before attempting to delete
            $checkQuery = $db->prepare("SELECT * FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
            $checkQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $id_user
            ]);

            if ($checkQuery->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'Aucune réservation trouvée à annuler.']);
                exit;
            }

            // Delete the booking
            $deleteQuery = $db->prepare("DELETE FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
            $deleteQuery->execute([
                ':id_covoiturage' => $covoiturageId,
                ':id_user' => $id_user
            ]);

            echo json_encode(['success' => true, 'message' => 'Votre demande de réservation a été annulée avec succès.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}