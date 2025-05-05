<?php
require_once '../../../Controller/ColisController.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../lib/PHPMailer/src/PHPMailer.php';
require '../../../lib/PHPMailer/src/SMTP.php';
require '../../../lib/PHPMailer/src/Exception.php';


session_start();

$ColisC = new ColisController();
$client = $ColisC->getClientById($colis['id_client']);

if (!isset($_GET['id_colis'])) {
  die("Invalid Request");
}

$id_colis = $_GET['id_colis'];
$colis = null;

$list = $ColisC->listColis();
foreach ($list as $c) {
  if ($c['id_colis'] == $id_colis) {
    $colis = $c;
    break;
  }
}

if (!$colis) {
  die("Colis not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $currentStatus = $colis['statut'];
  $newStatus = $currentStatus;

  if ($currentStatus === 'en attente') {
    $newStatus = 'en transit';
  } elseif ($currentStatus === 'en transit') {
    $newStatus = 'livré';
  } elseif ($currentStatus === 'livré') {
    $newStatus = 'en attente';
  }

  // Update the colis
  $ColisC->updateColis(
    $id_colis,
    $_POST['id_client'],
    $_POST['id_covoit'],
    $newStatus,
    $_POST['date_colis'],
    $_POST['longueur'],
    $_POST['largeur'],
    $_POST['hauteur'],
    $_POST['poids'],
    $_POST['lieu_ram'],
    $_POST['lieu_dest'],
    $_POST['latitude_ram'],
    $_POST['longitude_ram'],
    $_POST['latitude_dest'],
    $_POST['longitude_dest'],
    $_POST['prix']
  );

  // Only send mail if status is en transit or livré
  if (in_array($newStatus, ['en transit', 'livré']) && !empty($client['email'])) {
    $mail = new PHPMailer(true);

    try {
      // Server settings
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'hakimyessine72@gmail.com';
      $mail->Password   = 'hewe qyyb wabn krko'; // Use Gmail App Password
      $mail->SMTPSecure = 'tls';
      $mail->Port       = 587;

      // Recipients
      $mail->setFrom('hakimyessine72@gmail.com', 'TransitX');
      $mail->addAddress($client['email'], $client['nom'] . ' ' . $client['prenom']);

      // Content
      $mail->isHTML(true);
      $mail->Subject = 'Mise à jour du statut de votre colis';
      $mail->Body = "
      <p>Bonjour <strong>{$client['prenom']} {$client['nom']}</strong>,</p>
    
      <p>Nous vous informons que le statut de votre colis (ID : <strong>{$id_colis}</strong>) a été mis à jour.</p>
    
      <p><strong>Nouveau statut :</strong> <span style='color:#1f4f65;'>{$newStatus}</span></p>
    
      <p>Merci de faire confiance à <strong>TransitX</strong> pour vos livraisons durables.</p>
    
      <p style='margin-top:20px;'>Cordialement,<br>L'équipe TransitX</p>
    ";
    
      $mail->send();

    } catch (Exception $e) {
      error_log("Mailer Error: " . $mail->ErrorInfo);
    }
  }

  header("Location: colisList.php?updated_colis=" . $id_colis);
  exit;
}
?>

<form id="autoForm" method="POST" style="display:none;">
  <input type="hidden" name="id_client" value="<?= $_SESSION['user_id'] ?>">
  <input type="hidden" name="id_covoit" value="<?= htmlspecialchars($colis['id_covoit']) ?>">
  <input type="hidden" name="statut" value="<?= htmlspecialchars($colis['statut']) ?>">
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