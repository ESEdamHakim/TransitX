<?php
include '../../../Controller/buscontroller.php';
require_once '../../assets/PHPMailer/src/Exception.php';
require_once '../../assets/PHPMailer/src/PHPMailer.php';
require_once '../../assets/PHPMailer/src/SMTP.php';

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

if (
  $_SERVER["REQUEST_METHOD"] == "POST" &&
  isset(
  $_POST['id_trajet'],
  $_POST['num_bus'],
  $_POST['capacite'],
  $_POST['type_bus'],
  $_POST['marque'],
  $_POST['modele'],
  $_POST['date_mise_en_service'],
  $_POST['statut']
)
) {
  $id_trajet = $_POST['id_trajet'];
  $num_bus = $_POST['num_bus'];
  $capacite = $_POST['capacite'];
  $type_bus = $_POST['type_bus'];
  $marque = $_POST['marque'];
  $modele = $_POST['modele'];
  $date_mise_en_service = $_POST['date_mise_en_service'];
  $statut = $_POST['statut'];

  $bus = new Bus(
    $id_trajet,
    $num_bus,
    $capacite,
    $type_bus,
    $marque,
    $modele,
    $date_mise_en_service,
    $statut
  );

  $controller = new BusController();
  $id_bus = $controller->addBus($bus);
  $controller->notifyUsersAboutNewBus($id_trajet, $id_bus);
  $controller->notifyUsersByEmail($id_trajet, $id_bus);
  header("Location: crud.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TransitX - Ajouter un Bus</title>

  <!-- Styles -->
  <link rel="stylesheet" href="../../assets/css/main.css" />
  <link rel="stylesheet" href="assets/css/crud.css" />
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />

  <!-- Custom Styles -->
  <style>
    .section-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .section-header h2 {
      margin-top: 0.5rem;
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
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
          <span>Transit</span><span class="highlight">X</span>
        </div>
        <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
      </div>

      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li><a href="../users/crud.php"><i class="fas fa-users"></i><span>Utilisateurs</span></a></li>
            <li class="active"><a href="crud.php"><i class="fas fa-bus"></i><span>Bus</span></a></li>
            <li><a href="../colis/crud.php"><i class="fas fa-box"></i><span>Colis</span></a></li>
            <li><a href="../reclamations/crud.php"><i
                  class="fas fa-exclamation-circle"></i><span>Réclamations</span></a></li>
            <li><a href="../covoiturage/crud.php"><i class="fas fa-car-side"></i><span>Covoiturage</span></a></li>
            <li><a href="../blog/crud.php"><i class="fas fa-blog"></i><span>Blog</span></a></li>
          </ul>
        </nav>
      </div>

      <div class="sidebar-footer">
        <a href="#" class="user-profile">
          <img src="../assets/images/placeholder-admin.png" alt="Admin" class="user-img">
          <div class="user-info">
            <h4>Admin User</h4>
            <p>Administrateur</p>
          </div>
        </a>
        <a href="../../../index.php" class="logout">
          <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
        </a>
      </div>
    </aside>

    <main class="main-content">
      <section class="bus-form-section">
        <div class="container">
          <div class="section-header">
            <h2>Ajouter un Bus
              <p>Remplissez le formulaire ci-dessous pour enregistrer un nouveau bus.</p>
            </h2>
          </div>
          <div class="bus-form-container">
            <form class="bus-form" method="POST">
              <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                  <div class="form-group">
                    <label for="id_trajet">Trajet:</label>
                    <select name="id_trajet">
                      <option value="">Sélectionner un trajet</option>
                      <?php foreach ($trajets as $trajet): ?>
                        <option value="<?= htmlspecialchars($trajet['id_trajet']) ?>">
                          <?= htmlspecialchars($trajet['place_depart'] . " → " . $trajet['place_arrivee'] . " à " . $trajet['heure_depart']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="capacite">Capacité:</label>
                    <input type="number" name="capacite" id="capacite" placeholder="Entrez la capacité">
                  </div>

                  <div class="form-group">
                    <label for="marque">Marque:</label>
                    <input type="text" name="marque" id="marque" placeholder="Entrez la marque">
                  </div>

                  <div class="form-group">
                    <label for="statut">Statut:</label>
                    <select name="statut" id="statut">
                      <option value="">Sélectionner le statut</option>
                      <option value="actif">actif</option>
                      <option value="maintenance">maintenance</option>
                      <option value="inactif">inactif</option>
                    </select>
                  </div>
                </div>

                <!-- Right Column -->
                <div class="form-column">
                  <div class="form-group">
                    <label for="num_bus">Numéro Bus:</label>
                    <input type="text" name="num_bus" id="num_bus" placeholder="Entrez le numéro du bus">
                  </div>

                  <div class="form-group">
                    <label for="type_bus">Type de Bus:</label>
                    <select name="type_bus" id="type_bus">
                      <option value="">Sélectionner le type de bus</option>
                      <option value="Standard">standard</option>
                      <option value="Tourisme">tourisme</option>
                      <option value="Scolaire">scolaire</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="modele">Modèle:</label>
                    <input type="text" name="modele" id="modele" placeholder="Entrez le modèle">
                  </div>

                  <div class="form-group">
                    <label for="date_mise_en_service">Date de Mise en Service:</label>
                    <input type="date" name="date_mise_en_service" id="date_mise_en_service">
                  </div>
                </div>
              </div>

              <div class="form-actions text-center">
                <a href="crud.php" class="btn cancel" style="margin-left: 10px;">
                  Annuler <i class="fas fa-times"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                  Ajouter le Bus <i class="fas fa-plus"></i>
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