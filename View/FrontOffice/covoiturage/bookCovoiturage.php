<?php
require_once __DIR__ . '/../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../configuration/appConfig.php'; // Includes session_start()
require_once __DIR__ . '/mailcovoit.php'; // Include the mail logic
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log("Error: Invalid request method.");
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Debug: Log the received data
    error_log("Debug: Received data: " . print_r($data, true));

    $covoiturageId = $data['covoiturageId'] ?? null;
    $action = $data['action'] ?? null;

    if (!isset($id_user)) {
        error_log("Error: User not logged in.");
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    if (!$covoiturageId || !$id_user || !in_array($action, ['book', 'cancel'])) {
        error_log("Error: Invalid data provided. Covoiturage ID: $covoiturageId, User ID: $id_user, Action: $action");
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        exit;
    }

    $db = config::getConnexion();

    if ($action === 'book') {
        // Start a database transaction
        $db->beginTransaction();

        // Check if a booking already exists
        $checkQuery = $db->prepare("SELECT * FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
        $checkQuery->execute([
            ':id_covoiturage' => $covoiturageId,
            ':id_user' => $id_user
        ]);

        if ($checkQuery->rowCount() > 0) {
            error_log("Error: Booking already exists for Covoiturage ID: $covoiturageId, User ID: $id_user");
            echo json_encode(['success' => false, 'message' => 'Vous avez déjà envoyé une demande pour ce covoiturage.']);
            $db->rollBack(); // Rollback the transaction
            exit;
        }

        // Insert a new booking
        $insertQuery = $db->prepare("INSERT INTO bookings (id_covoiturage, id_user, notification_status) VALUES (:id_covoiturage, :id_user, 'pending')");
        $insertQuery->execute([
            ':id_covoiturage' => $covoiturageId,
            ':id_user' => $id_user
        ]);
        error_log("Debug: Booking created successfully for Covoiturage ID: $covoiturageId, User ID: $id_user");

        // Fetch the email of the covoiturage creator
        $creatorQuery = $db->prepare("SELECT u.email FROM user u JOIN covoiturage c ON u.id = c.id_user WHERE c.id_covoit = :id_covoiturage");
        $creatorQuery->execute([':id_covoiturage' => $covoiturageId]);
        $creatorEmail = $creatorQuery->fetchColumn();

        if ($creatorEmail) {
            error_log("Debug: Creator email fetched: $creatorEmail");

            // Send an email to the creator
            $subject = "Nouvelle demande de réservation pour votre covoiturage";
            $body = "Bonjour,<br><br>Un utilisateur a envoyé une demande pour rejoindre votre covoiturage.<br><br>Connectez-vous à votre compte pour voir les détails.";
            $mailSent = sendMail($creatorEmail, $subject, $body);

            if (!$mailSent) {
                error_log("Error: Failed to send email to $creatorEmail");
            } else {
                error_log("Debug: Email sent successfully to $creatorEmail");
            }
        } else {
            error_log("Error: Creator email not found for Covoiturage ID: $covoiturageId");
            $db->rollBack(); // Rollback the transaction
            echo json_encode(['success' => false, 'message' => 'Unable to fetch the creator\'s email.']);
            exit;
        }

        // Commit the transaction
        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Votre demande de réservation a été envoyée avec succès.']);
    } elseif ($action === 'cancel') {
        // Check if the booking exists before attempting to delete
        $checkQuery = $db->prepare("SELECT * FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
        $checkQuery->execute([
            ':id_covoiturage' => $covoiturageId,
            ':id_user' => $id_user
        ]);

        if ($checkQuery->rowCount() === 0) {
            error_log("Error: No booking found to cancel for Covoiturage ID: $covoiturageId, User ID: $id_user");
            echo json_encode(['success' => false, 'message' => 'Aucune réservation trouvée à annuler.']);
            exit;
        }

        // Delete the booking
        $deleteQuery = $db->prepare("DELETE FROM bookings WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user");
        $deleteQuery->execute([
            ':id_covoiturage' => $covoiturageId,
            ':id_user' => $id_user
        ]);
        error_log("Debug: Booking canceled successfully for Covoiturage ID: $covoiturageId, User ID: $id_user");

        echo json_encode(['success' => true, 'message' => 'Votre demande de réservation a été annulée avec succès.']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
}