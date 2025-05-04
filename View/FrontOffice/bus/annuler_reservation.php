<?php
include("../../../Controller/trajetcontroller.php");

$controller_trajet = new TrajetController();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_bus'])) {
  $id_bus = intval($_POST['id_bus']);
  $user_id = 1;

  try {
    $stmt = $controller_trajet->db->prepare("DELETE FROM bus_reservation WHERE id_bus = ? AND id_user = ?");
    $stmt->execute([$id_bus, $user_id]);

    // Increase available seats
    $stmt = $controller_trajet->db->prepare("UPDATE bus SET nbplacesdispo = nbplacesdispo + 1 WHERE id_bus = ?");
    $stmt->execute([$id_bus]);

    $response['success'] = true;
    $response['message'] = "Réservation annulée avec succès.";
  } catch (Exception $e) {
    $response['message'] = "Erreur lors de l'annulation : " . $e->getMessage();
  }
}

echo json_encode($response);
?>
