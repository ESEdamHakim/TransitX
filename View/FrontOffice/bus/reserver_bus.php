<?php
require_once __DIR__ . '/../../../Controller/trajetcontroller.php';
session_start();
header('Content-Type: application/json');

$response = ['success' => false];

if (!isset($_POST['id_bus'])) {
  $response['message'] = "ID du bus manquant.";
  echo json_encode($response);
  exit;
}

$id_bus = intval($_POST['id_bus']);

try {
  $controller_trajet = new TrajetController();
  $bus = $controller_trajet->getBusById($id_bus);

  if (!$bus) {
    $response['message'] = "Bus introuvable.";
  } elseif ($bus['nbplacesdispo'] <= 0) {
    $response['message'] = "Aucune place disponible pour ce bus.";
  } else {
    // Insert reservation
    $stmt = $controller_trajet->db->prepare("INSERT INTO bus_reservation (id_bus, id_user) VALUES (?, ?)");
    $stmt->execute([$id_bus, $_SESSION['user_id']]);

    // Update available seats
    $stmt = $controller_trajet->db->prepare("UPDATE bus SET nbplacesdispo = nbplacesdispo - 1 WHERE id_bus = ? AND nbplacesdispo > 0");
    $stmt->execute([$id_bus]);

    $response['success'] = true;
    $response['message'] = "Réservation réussie !";

    // Send email if payment is by card
    if (isset($_POST['payment']) && $_POST['payment'] === 'card') {
      $userEmail = $controller_trajet->getEmailByUserId($_SESSION['user_id']);
      if ($userEmail) {
        $subject = "🎫 Confirmation de votre réservation TransitX";
        $message = 
          "Bonjour,\n\n" .
          "Votre paiement par carte a été accepté et votre réservation de bus a été confirmée.\n\n" .
          "🚌 Détails de la réservation :\n" .
          "- Bus n°: " . htmlspecialchars($bus['num_bus']) . "\n" .
          "- Départ : " . htmlspecialchars($bus['ville_depart']) . "\n" .
          "- Arrivée : " . htmlspecialchars($bus['ville_arrivee']) . "\n" .
          "- Date : " . htmlspecialchars($bus['date_depart']) . "\n" .
          "- Heure : " . htmlspecialchars($bus['heure_depart']) . "\n\n" .
          "Merci d'utiliser TransitX !\n\n" .
          "Cordialement,\nL'équipe TransitX";
        $headers = "From: no-reply@transitx.com\r\nContent-Type: text/plain; charset=UTF-8";
        mail($userEmail, $subject, $message, $headers);
      }
    }
  }
} catch (Exception $e) {
  $response['message'] = "Erreur lors de la réservation : " . $e->getMessage();
}

echo json_encode($response);
?>