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
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/colis.css">
  <link rel="stylesheet" href="../../assets/css/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
</head>
<style>
  /* Additional styles specific to colis list */
  .colis-dashboard {
    padding: 3rem 5%;
    background-color: var(--background);
  }

  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }

  .dashboard-title {
    color: var(--secondary);
  }

  .dashboard-title h1 {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
  }

  .dashboard-title p {
    color: #666;
  }

  .dashboard-actions {
    display: flex;
    gap: 1rem;
  }

  .filters-section {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .filters-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .filters-title h3 {
    font-size: 1.2rem;
    color: var(--secondary);
    margin: 0;
  }

  .filters-toggle {
    background: none;
    border: none;
    color: var(--primary);
    cursor: pointer;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .filters-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
  }

  .filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .filter-group label {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
  }

  .filter-group select,
  .filter-group input {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f8f9fa;
  }

  .filter-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
  }

  .tabs-container {
    display: flex;
    border-bottom: 1px solid #ddd;
    margin-bottom: 1.5rem;
    overflow-x: auto;
  }

  .tab {
    padding: 1rem 1.5rem;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s;
    white-space: nowrap;
  }

  .tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
  }

  .tab:hover {
    color: var(--primary);
  }

  .tab .count {
    display: inline-block;
    background-color: #f1f1f1;
    color: #666;
    font-size: 0.8rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    margin-left: 0.5rem;
  }

  .tab.active .count {
    background-color: var(--primary);
    color: white;
  }

  .action {
    display: flex;
    gap: 0.5rem;
  }

  .empty-state {
    text-align: center;
    padding: 3rem;
    color: #666;
  }

  .empty-state i {
    font-size: 3rem;
    color: #ddd;
    margin-bottom: 1rem;
  }

  .empty-state h3 {
    margin-bottom: 0.5rem;
    color: #333;
  }

  @media (max-width: 992px) {
    .dashboard-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .dashboard-actions {
      width: 100%;
    }

    .filters-content {
      grid-template-columns: 1fr;
    }
  }
</style>
</head>

<body>
  <header class="landing-header">
    <div class="container">
      <div class="header-left">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="main-logo">
          <span class="logo-text">TransitX</span>
        </div>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="../index.php">Accueil</a></li>
          <li><a href="../bus/index.php">Bus</a></li>
          <li class="active"><a href="index.php">Colis</a></li>
          <li><a href="../covoiturage/index.php">Covoiturage</a></li>
          <li><a href="../blog/index.php">Blog</a></li>
          <li><a href="../reclamation/index.php">Réclamation</a></li>
          <li><a href="../vehicule/index.php">Véhicule</a></li>

        </ul>
      </nav>
      <div class="header-right">
        <div class="actions-container">
          <?php include '../assets/php/profile.php'; ?>
        </div>
        <!-- Notification Button -->
        <button class="notify-button position-relative" title="Notifications">
          <i class="fa-regular fa-bell text-2xl" style="color: #86b391;"></i>
          <!-- Notification Badge -->
          <?php if (count($notifications) > 0): ?>
            <span
              class="notif-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full badge-pulse">
              <?= count($notifications) ?>
            </span>
          <?php endif; ?>
        </button>
      </div>
    </div>
  </header>

  <main>
    <section class="colis-dashboard">
      <div class="container">
        <div class="colis-form">
          <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
  <h2 style="display: flex; align-items: center; text-align: center; font-size: 1.5rem; margin: 0; color: var(--secondary); font-weight: 1000;">
    <i class="fas fa-box-open" style="margin-right: 10px; color: var(--primary);"></i>
    Colis Affectés à Mes Covoiturages
    <i class="fas fa-map-marker-alt" style="margin-left: 10px; font-size: 1.3rem; color: var(--primary);"></i>
  </h2>
</div>

          <div id="colis-map" style="width:100%;height:500px;border-radius:12px;"></div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../../assets/footer.php'; ?>

  <div id="notificationModal" class="notimodal-overlay hidden">
    <div class="notimodal-content">
      <div class="notimodal-header">
        <h3>Mes Notifications</h3>
        <button id="closeModal" class="close-btn">&times;</button>
      </div>
      <div class="notimodal-body">
        <div class="notifications-scroll">
          <?php foreach ($notifications as $notif): ?>
            <div class="notification-item card p-3 mb-3 shadow-sm rounded bg-light">
              <ul class="mb-2">
                <li><strong> Colis :</strong> <?= htmlspecialchars($notif['lieu_ram']) ?> ➜
                  <?= htmlspecialchars($notif['lieu_dest']) ?>
                </li>
                <li><strong>Date :</strong> <?= htmlspecialchars($notif['date_colis']) ?></li>
                <li><strong>Prix :</strong> <?= htmlspecialchars($notif['prix']) ?> TND</li>
                <li><strong>Dimensions (L×l×H) :</strong> <?= htmlspecialchars($notif['longueur']) ?>cm ×
                  <?= htmlspecialchars($notif['largeur']) ?>cm × <?= htmlspecialchars($notif['hauteur']) ?>cm
                </li>
                <li><strong>Poids :</strong> <?= htmlspecialchars($notif['poids']) ?> kg</li>
              </ul>

              <p class="mb-0">
                <strong>Covoiturage :</strong>
                <?= $notif['id_covoit'] ? "Affecté (ID : " . htmlspecialchars($notif['id_covoit']) . ")" : "<span class='text-muted'>Non encore affecté</span>" ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Pass PHP data to JS
    const colisMarkers = <?= json_encode($listByCovoit) ?>;
  </script>
  <script src="assets/js/colisMaps.js"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRqtevb1ZGEqlL_tceScv_8nI-XccCsrI&libraries=places&callback=initMap"
    async defer></script>
  <script>
    const notifyBtn = document.querySelector('.notify-button');
    const modal = document.getElementById('notificationModal');
    const closeModal = document.getElementById('closeModal');

    notifyBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    // Optional: close modal when clicking outside
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  </script>
  <script src="../assets/js/profile.js"></script>

</body>

</html>