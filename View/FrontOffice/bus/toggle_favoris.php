<?php
include("../../../Controller/trajetcontroller.php");
session_start();
$id_trajet = $_POST['id_trajet'] ?? null;
$action = $_POST['action'] ?? null;

$controller =  new TrajetController();

if ($id_trajet && $action) {
  try {
    if ($action === 'add') {
      $controller->addFavori($_SESSION['user_id'], $id_trajet);
    } elseif ($action === 'remove') {
      $controller->removeFavori($_SESSION['user_id'], $id_trajet);
    }
    echo json_encode(['success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
}
?>
