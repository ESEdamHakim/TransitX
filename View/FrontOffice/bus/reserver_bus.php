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

<body>
  <?php
  include(__DIR__ . "/../../../Controller/buscontroller.php");

  $controller = new BusController();
  $error_message = '';
  $success_message = '';

  if (!isset($_POST['id_bus'])) {
    $error_message = "ID du bus manquant.";
  }

  $id_bus = intval($_POST['id_bus']);
  $user_id = 1;

  try {
    $bus = $controller->getBusById($id_bus);

    if (!$bus) {
      $error_message = "Bus introuvable.";
    }

    if ($bus['nbplacesdispo'] <= 0) {
      $error_message = "Aucune place disponible pour ce bus.";
    }

    $db = config::getConnexion();
    $stmt = $db->prepare("SELECT COUNT(*) FROM bus_reservation WHERE id_bus = ? AND id_user = ?");
    $stmt->execute([$id_bus, $user_id]);
    $reservationExists = $stmt->fetchColumn();

    if ($reservationExists > 0) {
      $error_message = "Vous avez déjà réservé une place pour ce bus.";
    }

    if (!$error_message) {
      $stmt = $db->prepare("INSERT INTO bus_reservation (id_bus, id_user) VALUES (?, ?)");
      $stmt->execute([$id_bus, $user_id]);

      $stmt = $db->prepare("UPDATE bus SET nbplacesdispo = nbplacesdispo - 1 WHERE id_bus = ?");
      $stmt->execute([$id_bus]);

      $success_message = "Réservation réussie !";
    }

  } catch (Exception $e) {
    $error_message = "Erreur lors de la réservation : " . $e->getMessage();
  }
  ?>

  <header class="landing-header">
    <div class="container"></div>

    <!-- Success Modal -->
    <?php if ($success_message): ?>
      <div class="bus-info-modal show" id="reservation-success-modal">
        <div class="modal-content">
          <div class="modal-header">
            <h4>Succès</h4>
          </div>
          <div class="modal-body">
            <?php echo htmlspecialchars($success_message); ?>
          </div>
          <div class="modal-footer">
            <a href="index.php" class="btn-secondary">Retour à la liste des trajets</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Error Modal -->
    <?php if ($error_message): ?>
      <div class="bus-info-modal show" id="reservation-error-modal">
        <div class="modal-content">
          <div class="modal-header">
            <h4>Erreur</h4>
          </div>
          <div class="modal-body">
            <?php echo htmlspecialchars($error_message); ?>
          </div>
          <div class="modal-footer">
            <a href="index.php" class="btn-secondary">Retour à la liste des trajets</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </header>

  <script>
    // Modal close functionality
    window.addEventListener('click', function (event) {
      const successModal = document.getElementById('reservation-success-modal');
      const errorModal = document.getElementById('reservation-error-modal');
      if (event.target === successModal || event.target === errorModal) {
        successModal.classList.remove('show');
        errorModal.classList.remove('show');
      }
    });
  </script>

</body>

</html>
