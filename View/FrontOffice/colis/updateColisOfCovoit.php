<?php
require_once '../../../Controller/ColisController.php';

// Require PHPMailer classes first
require '../../assets/PHPMailer/src/Exception.php';
require '../../assets/PHPMailer/src/PHPMailer.php';
require '../../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$ColisC = new ColisController();

if (!isset($_GET['id_colis'])) {
  die("Invalid Request");
}

$id_colis = $_GET['id_colis'];
$colis = $ColisC->getColisById($id_colis);

if (!$colis) {
  die("Colis not found");
}

$client = $ColisC->getClientById($colis['id_client']);
if (!$client) {
  error_log("Client not found for colis ID: {$id_colis}");
  die("Client not found");
}
if (!$client) {
  error_log("Client not found for colis ID: {$id_colis}");
  die("Client not found");
} else {
  error_log("Client email: " . $client['email']);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $currentStatus = $colis['statut'];
  $newStatus = $currentStatus;

  if ($currentStatus === 'en attente') {
    $newStatus = 'en transit';
  } elseif ($currentStatus === 'en transit') {
    $newStatus = 'livré';
  } elseif ($currentStatus === 'livré') {
    $newStatus = 'en attente';
  }

  // Fallbacks for each field if not sent via POST
  $id_client = $_POST['id_client'] ?? $colis['id_client'];
  $id_covoit = $_POST['id_covoit'] ?? $colis['id_covoit'];
  $date_colis = $_POST['date_colis'] ?? $colis['date_colis'];
  $longueur = $_POST['longueur'] ?? $colis['longueur'];
  $largeur = $_POST['largeur'] ?? $colis['largeur'];
  $hauteur = $_POST['hauteur'] ?? $colis['hauteur'];
  $poids = $_POST['poids'] ?? $colis['poids'];
  $lieu_ram = $_POST['lieu_ram'] ?? $colis['lieu_ram'];
  $lieu_dest = $_POST['lieu_dest'] ?? $colis['lieu_dest'];
  $latitude_ram = $_POST['latitude_ram'] ?? $colis['latitude_ram'];
  $longitude_ram = $_POST['longitude_ram'] ?? $colis['longitude_ram'];
  $latitude_dest = $_POST['latitude_dest'] ?? $colis['latitude_dest'];
  $longitude_dest = $_POST['longitude_dest'] ?? $colis['longitude_dest'];
  $prix = $_POST['prix'] ?? $colis['prix'];

  // Update the colis
  $ColisC->updateColis(
    $id_colis,
    $id_client,
    $id_covoit,
    $newStatus,
    $date_colis,
    $longueur,
    $largeur,
    $hauteur,
    $poids,
    $lieu_ram,
    $lieu_dest,
    $latitude_ram,
    $longitude_ram,
    $latitude_dest,
    $longitude_dest,
    $prix
  );

  // Send email only if status changed to en transit or livré
  if (in_array($newStatus, ['en transit', 'livré']) && !empty($client['email'])) {
    $mail = new PHPMailer(true);

    try {
      // Server settings
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'hakimyessine72@gmail.com';
      $mail->Password = 'hewe qyyb wabn krko'; // App-specific password
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      // Recipients
      $mail->setFrom('hakimyessine72@gmail.com', 'TransitX');
      $mail->addAddress($client['email']);

      // Email content
      $mail->isHTML(true);
      $mail->Subject = 'Mise a jour du statut de votre colis';
      $mail->Body = "
      <p>Bonjour <strong>{$client['prenom']} {$client['nom']}</strong>,</p>
    
      <p>Nous vous informons que le statut de votre colis (ID : <strong>{$id_colis}</strong>) a été mis à jour.</p>
    
      <p><strong>Nouveau statut :</strong> <span style='color:#1f4f65;'>{$newStatus}</span></p>
    
      <p><strong>Détails du colis :</strong><br>
      Dimensions : {$longueur}cm x {$largeur}cm x {$hauteur}cm<br>
      Poids : {$poids}kg<br>
      Lieu de ramassage : {$lieu_ram}<br>
      Destination : {$lieu_dest}<br>
      Prix : {$prix} DT
      </p>
    
      <p><strong>Trajet de covoiturage :</strong> de <strong>{$lieu_ram}</strong> à <strong>{$lieu_dest}</strong></p>
      <p><strong>Date du covoiturage :</strong> {$date_colis}</p>
    
      <p>Merci de faire confiance à <strong>TransitX</strong> pour vos livraisons durables.</p>
    
      <p style='margin-top:20px;'>Cordialement,<br>L'équipe TransitX</p>
    ";    

      $mail->send();

    } catch (Exception $e) {
      error_log("Mailer Error: " . $mail->ErrorInfo);
      error_log("Exception: " . $e->getMessage());
    }
  }
  header("Location: colisList.php?updated_colis=" . $id_colis);
  exit;
}
?>
<form id="autoForm" method="POST" style="display:none;">
  <input type="hidden" name="id_client" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
  <input type="hidden" name="id_covoit" value="<?= htmlspecialchars($colis['id_covoit']) ?>">
  <input type="hidden" name="date_colis" value="<?= htmlspecialchars($colis['date_colis']) ?>">
  <input type="hidden" name="longueur" value="<?= htmlspecialchars($colis['longueur']) ?>">
  <input type="hidden" name="largeur" value="<?= htmlspecialchars($colis['largeur']) ?>">
  <input type="hidden" name="hauteur" value="<?= htmlspecialchars($colis['hauteur']) ?>">
  <input type="hidden" name="poids" value="<?= htmlspecialchars($colis['poids']) ?>">
  <input type="hidden" name="lieu_ram" value="<?= htmlspecialchars($colis['lieu_ram']) ?>">
  <input type="hidden" name="lieu_dest" value="<?= htmlspecialchars($colis['lieu_dest']) ?>">
  <input type="hidden" name="latitude_ram" value="<?= htmlspecialchars($colis['latitude_ram']) ?>">
  <input type="hidden" name="longitude_ram" value="<?= htmlspecialchars($colis['longitude_ram']) ?>">
  <input type="hidden" name="latitude_dest" value="<?= htmlspecialchars($colis['latitude_dest']) ?>">
  <input type="hidden" name="longitude_dest" value="<?= htmlspecialchars($colis['longitude_dest']) ?>">
  <input type="hidden" name="prix" value="<?= htmlspecialchars($colis['prix']) ?>">
</form>

<script>
  window.onload = function () {
    document.getElementById('autoForm').submit();
  };
</script>