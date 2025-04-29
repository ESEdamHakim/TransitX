<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Services de Bus</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>

<?php
include(__DIR__ . "/../../../Controller/buscontroller.php");

$controller = new BusController();

// Check if bus ID was sent
if (!isset($_POST['id_bus'])) {
    die("ID du bus manquant.");
}

$id_bus = intval($_POST['id_bus']);
$user_id = 1;

try {
    // Fetch bus by ID using controller
    $bus = $controller->getBusById($id_bus);

    if (!$bus) {
        die("Bus introuvable.");
    }

    if ($bus['nbplacesdispo'] <= 0) {
        die("Aucune place disponible pour ce bus.");
    }

    // Check if the user has already reserved this bus
    $db = config::getConnexion();
    $stmt = $db->prepare("SELECT COUNT(*) FROM bus_reservation WHERE id_bus = ? AND id_user = ?");
    $stmt->execute([$id_bus, $user_id]);
    $reservationExists = $stmt->fetchColumn();

    if ($reservationExists > 0) {
        die("Vous avez déjà réservé une place pour ce bus.");
    }

    // Insert into reservations table
    $stmt = $db->prepare("INSERT INTO bus_reservation (id_bus, id_user) VALUES (?, ?)");
    $stmt->execute([$id_bus, $user_id]);

    // Update available seats
    $stmt = $db->prepare("UPDATE bus SET nbplacesdispo = nbplacesdispo - 1 WHERE id_bus = ?");
    $stmt->execute([$id_bus]);


} catch (Exception $e) {
    die("Erreur lors de la réservation : " . $e->getMessage());
}
?>
<body>
  <header class="landing-header">
    <div class="container"></div>
    <!-- Modal -->
    <div class="bus-info-modal" >
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-header">Succès</h4>
          </div>
          <div class="modal-body">
            Réservation réussie !
          </div>
          <div class="modal-footer">
            <a href="index.php" class="btn btn-secondary">Retour à la liste des trajets</a>
          </div>
        </div>
      </div>
    </div>
</header>
</body>