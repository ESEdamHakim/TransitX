<?php
require_once __DIR__ . '/../../../Controller/ColisController.php';
require_once __DIR__ . '/../../../Controller/userC.php';

session_start(); // Important : Démarrer la session en haut du fichier

$userController = new UserC();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// For testing - use the first user from the list instead of session user
// Comment this out once testing is complete
$currentUser = null;
$currentUser = null;

if (isset($_SESSION['user_id'])) {
  $currentUser = $userController->showUser($_SESSION['user_id']);
}


$ColisC = new ColisController();
$list = $ColisC->listColis();
$listByCovoit = $ColisC->getColisByCovoiturage($_SESSION['user_id']);
$covoiturages = $ColisC->getAllCovoiturages();
$clients = $ColisC->getAllClients();
$notifications = $ColisC->getNotificationByIdUser($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Mes Colis</title>
  <link rel="stylesheet" href="../../assets/css/profile.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
  <div class="dashboard">
    <?php include 'sidebar.php'; ?>


    <main class="main-content">
      <header class="dashboard-header">
        <div style="display: flex; justify-content: center;">
          <h2
            style="display: flex; align-items: center; text-align: center; font-size: 1.5rem; margin: 0; color: var(--secondary); font-weight: 1000;">
            <i class="fas fa-box-open" style="margin-right: 10px; color: var(--primary);"></i>
            Colis Affectés à Mes Covoiturages
            <i class="fas fa-map-marker-alt" style="margin-left: 10px; font-size: 1.3rem; color: var(--primary);"></i>
          </h2>
        </div>
      </header>
      <div class="dashboard-content">
        <div class="crud-container">
          <div id="colis-map" style="width:100%;height:500px;border-radius:12px;"></div>
        </div>
      </div>
    </main>

    <script>
      // Pass PHP data to JS
      const colisMarkers = <?= json_encode($list) ?>;
    </script>
    <script src="assets/js/colisMaps.js"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRqtevb1ZGEqlL_tceScv_8nI-XccCsrI&libraries=places&callback=initMap"
      async defer></script>
    <script src="../assets/js/profile.js"></script>

</body>

</html>