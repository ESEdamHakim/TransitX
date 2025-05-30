<?php
require_once __DIR__ . '/../../../Controller/buscontroller.php';
require_once __DIR__ . '/../../assets/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../assets/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendBusEmailNotification($toEmail, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hakimedam72@gmail.com'; // Replace with a real Gmail address
        $mail->Password = 'anqz mbku mwvl desj';    // App-specific password from Gmail
        $mail->SMTPSecure = 'tls'; // Use 'ssl' if you use port 465
        $mail->Port = 587;

        // Email headers and content
        $mail->setFrom('hakimedam72@gmail.com', 'TransitX');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($body); // Converts newlines to <br> for HTML formatting

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

$busController = new BusController();
$trajets = $busController->getAllTrajets();
$bus = null;

if (isset($_GET["id_bus"])) {
  $bus = $busController->getBusById($_GET["id_bus"]);
}

if (!$bus) {
  die("Bus introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_bus = $_POST['id_bus'];
  $new_id_trajet = $_POST['id_trajet'];
  $num_bus = $_POST['num_bus'];
  $capacite = $_POST['capacite'];
  $type_bus = $_POST['type_bus'];
  $marque = $_POST['marque'];
  $modele = $_POST['modele'];
  $date_mise_en_service = $_POST['date_mise_en_service'];
  $statut = $_POST['statut'];

  // Get the old bus to compare trajet
  $oldBus = $busController->getBusByIdObject($id_bus);
  $old_id_trajet = $oldBus ? $oldBus->getIdTrajet() : null;

  // Create updated bus object
  $updatedBus = new Bus(
    $new_id_trajet,
    $num_bus,
    $capacite,
    $type_bus,
    $marque,
    $modele,
    $date_mise_en_service,
    $statut
  );
  $updatedBus->setIdBus($id_bus);

  // Update bus
  $busController->updateBus($updatedBus);

  // Only notify if the trajet changed
  if ($old_id_trajet !== $new_id_trajet) {
    $busController->notifyUsersAboutNewBus($new_id_trajet, $id_bus);
    $busController->notifyUsersByEmail($new_id_trajet, $id_bus);

  }

  header("Location: crud.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TransitX - Modifier le Bus</title>

  <!-- Styles -->
  <link rel="stylesheet" href="../../assets/css/main.css" />
  <link rel="stylesheet" href="assets/css/crud.css" />
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />

  <style>
    .section-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .form-actions {
      margin-top: 1.5rem;
    }

    .form-group select {
      text-align: left;
      direction: ltr;
    }
  </style>
</head>

<body>
  <div class="dashboard">
        <?php include 'sidebar.php'; ?>
    <main class="main-content">
      <section class="bus-form-section">
        <div class="container">
          <div class="section-header">
            <h2>Modifier le Bus
              <p>Modifiez les informations du bus sélectionné.</p>
            </h2>
          </div>
          <div class="bus-form-container">
            <form class="bus-form" method="POST">
              <input type="hidden" name="id_bus" value="<?= $bus['id_bus'] ?>">

              <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                  <div class="form-group">
                    <label for="id_trajet">Trajet:</label>
                    <select name="id_trajet" id="id_trajet">
                      <option value="">Sélectionner un trajet</option>
                      <?php foreach ($trajets as $trajet): ?>
                        <option value="<?= htmlspecialchars($trajet['id_trajet']) ?>"
                          <?= $trajet['id_trajet'] == $bus['id_trajet'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($trajet['place_depart'] . " → " . $trajet['place_arrivee'] . " à " . $trajet['heure_depart']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="capacite">Capacité:</label>
                    <input type="number" name="capacite" id="capacite" value="<?= $bus['capacite'] ?>">
                  </div>

                  <div class="form-group">
                    <label for="marque">Marque:</label>
                    <input type="text" name="marque" id="marque" value="<?= $bus['marque'] ?>">
                  </div>

                  <div class="form-group">
                    <label for="statut">Statut:</label>
                    <select name="statut" id="statut">
                      <option value="actif" <?= $bus['statut'] == 'actif' ? 'selected' : '' ?>>actif</option>
                      <option value="maintenance" <?= $bus['statut'] == 'maintenance' ? 'selected' : '' ?>>maintenance
                      </option>
                      <option value="inactif" <?= $bus['statut'] == 'inactif' ? 'selected' : '' ?>>inactif</option>
                    </select>
                  </div>
                </div>

                <!-- Right Column -->
                <div class="form-column">
                  <div class="form-group">
                    <label for="num_bus">Numéro Bus:</label>
                    <input type="text" name="num_bus" id="num_bus" value="<?= $bus['num_bus'] ?>">
                  </div>

                  <div class="form-group">
                    <label for="type_bus">Type de Bus:</label>
                    <select name="type_bus" id="type_bus">
                      <option value="Standard">standard</option>
                      <option value="Tourisme">tourisme</option>
                      <option value="Scolaire">scolaire</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="modele">Modèle:</label>
                    <input type="text" name="modele" id="modele" value="<?= $bus['modele'] ?>">
                  </div>

                  <div class="form-group">
                    <label for="date_mise_en_service">Date de Mise en Service:</label>
                    <input type="date" name="date_mise_en_service" id="date_mise_en_service"
                      value="<?= $bus['date_mise_en_service'] ?>">
                  </div>
                </div>
              </div>

              <div class="form-actions text-center">
              <a href="crud.php" class="btn cancel" style="margin-left: 10px;">
                  Annuler <i class="fas fa-times"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                  Mettre à jour <i class="fas fa-edit"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>
  <script src="assets/js/busvalidation.js"></script>
</body>

</html>