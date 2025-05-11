<?php

require_once '../../../Controller/CovoiturageC.php';

if (isset($_POST['id_covoit'])) {
    $id_covoit = $_POST['id_covoit'];
    $covoiturageController = new CovoiturageC();

    try {
        // Get the database connection
        $db = config::getConnexion();

        // Delete related rows in the bookings table
        $deleteBookingsQuery = $db->prepare("DELETE FROM bookings WHERE id_covoiturage = :id_covoit");
        $deleteBookingsQuery->execute([':id_covoit' => $id_covoit]);

        // Delete related rows in the notifications table (if applicable)
        $deleteNotificationsQuery = $db->prepare("DELETE FROM notifications WHERE id_covoiturage = :id_covoit");
        $deleteNotificationsQuery->execute([':id_covoit' => $id_covoit]);

        // Delete the covoiturage
        $covoiturageController->deleteCovoiturage($id_covoit, null, true);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID non fourni']);
}