<?php
include("../../../Controller/trajetcontroller.php");

header('Content-Type: application/json');

$response = ['success' => false];

if (!isset($_POST['id_bus'])) {
  $response['message'] = "ID du bus manquant.";
  echo json_encode($response);
  exit;
}

$id_bus = intval($_POST['id_bus']);
$user_id = 1; // Replace with actual user ID from session

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
    $stmt->execute([$id_bus, $user_id]);

    // Update available seats
    $stmt = $controller_trajet->db->prepare("UPDATE bus SET nbplacesdispo = nbplacesdispo - 1 WHERE id_bus = ?");
    $stmt->execute([$id_bus]);

    $response['success'] = true;
    $response['message'] = "Réservation réussie !";
  }
} catch (Exception $e) {
  $response['message'] = "Erreur lors de la réservation : " . $e->getMessage();
}

echo json_encode($response);
?>
