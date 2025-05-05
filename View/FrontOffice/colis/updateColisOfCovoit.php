<?php
require_once '../../../Controller/ColisController.php';

session_start();

$ColisC = new ColisController();

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