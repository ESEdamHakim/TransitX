<?php
//session_start();
include("../../../Controller/trajetcontroller.php");
$user_id = 1; // replace with $_SESSION['user_id'] if logged in
$id_trajet = $_POST['id_trajet'] ?? null;
$action = $_POST['action'] ?? null;

$controller =  new TrajetController();

if ($id_trajet && $action) {
  try {
    if ($action === 'add') {
      $controller->addFavori($user_id, $id_trajet);
    } elseif ($action === 'remove') {
      $controller->removeFavori($user_id, $id_trajet);
    }
    echo json_encode(['success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
}
?>
