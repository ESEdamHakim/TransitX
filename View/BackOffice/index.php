<?php
require_once __DIR__ . '/../../Controller/UserC.php';

$userController = new UserC();
$serviceCounts = $userController->getServicesDistributionCounts();
$dashboardStats = $userController->getDashboardStats();
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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Dashboard</title>
  <link rel="stylesheet" href="../assets/css/profile.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../assets/chatbot/chatbot.css">

  <style>
    .reports-section {
      margin-top: 2rem;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
    }

    .reports-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .reports-header h3 {
      font-size: 1.2rem;
      color: var(--secondary-1);
    }

    .reports-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid var(--gray-light);
      padding-bottom: 0.5rem;
    }

    .report-tab {
      padding: 0.5rem 1rem;
      border: none;
      background: none;
      cursor: pointer;
      font-weight: 500;
      color: #666;
      position: relative;
    }

    .report-tab.active {
      color: var(--primary);
    }

    .report-tab.active::after {
      content: '';
      position: absolute;
      bottom: -0.5rem;
      left: 0;
      width: 100%;
      height: 2px;
      background-color: var(--primary);
    }

    .report-content {
      display: none;
    }

    .report-content.active {
      display: block;
    }

    .report-summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .summary-item {
      background-color: var(--gray-light);
      padding: 1rem;
      border-radius: 8px;
      text-align: center;
    }

    .summary-item .value {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .summary-item .label {
      font-size: 0.9rem;
      color: #666;
    }

    .report-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
    }

    .report-table th,
    .report-table td {
      padding: 0.75rem 1rem;
      text-align: left;
      border-bottom: 1px solid var(--gray-light);
    }

    .report-table th {
      font-weight: 600;
      color: var(--secondary-1);
      background-color: var(--gray-light);
    }

    .report-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .btn-outline {
      padding: 0.5rem 1rem;
      border: 1px solid var(--gray);
      background-color: white;
      border-radius: 4px;
      color: #666;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
    }

    .btn-outline:hover {
      background-color: var(--gray-light);
    }
  </style>
</head>

<body>
  <?php include '../assets/chatbot/chatbot.php'; ?>

  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <a href="../FrontOffice/index.php" class="logoback-link">
          <div class="logoback">
            <img src="../assets/images/logo.png" alt="TransitX Logoback" class="nav-logo">
            <span class="logo-text">Transit</span><span class="highlight logo-text">X</span>
          </div>
        </a>
        <button class="sidebar-toggle">
          <i class="fas fa-bars"></i>
        </button>
      </div>
      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li class="active">
              <a href="index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="users/crud.php">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
              </a>
            </li>
            <li>
              <a href="bus/crud.php">
                <i class="fas fa-bus"></i>
                <span>Bus</span>
              </a>
            </li>
            <li><a href="trajets/crud.php">
                <i class="fas fa-road"></i>
                <span>Trajets</span>
              </a>
            </li>
            <li>
              <a href="colis/crud.php">
                <i class="fas fa-box"></i>
                <span>Colis</span>
              </a>
            </li>
            <li>
              <a href="reclamations/crud.php">
                <i class="fas fa-exclamation-circle"></i>
                <span>Réclamations</span>
              </a>
            </li>
            <li>
              <a href="covoiturage/crud.php">
                <i class="fas fa-car-side"></i>
                <span>Covoiturage</span>
              </a>
            </li>
            <li>
              <a href="blog/crud.php">
                <i class="fas fa-blog"></i>
                <span>Blog</span>
              </a>
            </li>
            <li><a href="vehicule/crud.php"><i class="fas fa-car"></i><span>Véhicules</span></a></li>

          </ul>
          <a href="../../index.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
          </a>
        </nav>
      </div>
    </aside>

    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Dashboard</h1>
          <p>Bienvenue sur le tableau de bord TransitX</p>
        </div>
        <div class="header-right">
          <button id="hostMeetingBtn" class="btn-outline"
            style="margin-left: 12px; display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#1f4f65" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;">
              <rect x="3" y="7" width="14" height="10" rx="2" />
              <path d="M17 9l4 3-4 3V9z" />
            </svg>
            <span>Héberger une réunion</span>
          </button>
          <button onclick="window.location.href='blog/todo.php'" class="btn primary" style="margin-left:10px;">
            <i class="fas fa-check-square"></i> To-Do List
          </button>
          <button id="openMessagerieButton" style="margin-left:16px;">
            <!-- Envelope icon for messaging -->
            <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="none" stroke="currentColor"
              stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 48 48"
              style="display:block; margin:auto;">
              <rect x="6" y="12" width="36" height="24" rx="6" fill="#f5e9b9" stroke="#1f4f65" />
              <polyline points="6,12 24,30 42,12" fill="none" stroke="#1f4f65" stroke-width="3" />
            </svg>
          </button>
          <div class="actions-container">
            <?php include 'assets/php/indexprofile.php'; ?>
          </div>

        </div>
      </header>

      <div class="dashboard-content">
        <!-- Stats Cards -->
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon users">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
              <h3><?= $dashboardStats['utilisateurs'] ?></h3>
              <p>Utilisateurs</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon rides">
              <i class="fas fa-car-side"></i>
            </div>
            <div class="stat-details">
              <h3><?= $dashboardStats['trajets'] ?></h3>
              <p>Trajets</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon parcels">
              <i class="fas fa-box"></i>
            </div>
            <div class="stat-details">
              <h3><?= $dashboardStats['colis'] ?></h3>
              <p>Colis</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon complaints">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-details">
              <h3><?= $dashboardStats['reclamations'] ?></h3>
              <p>Réclamations</p>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
          <div class="chart-container">
            <div class="chart-header">
              <h3>Top Services utilisés</h3>
            </div>
            <div class="chart">
              <canvas id="topServices"></canvas>
            </div>
          </div>

          <div class="chart-container">
            <div class="chart-header">
              <h3>Répartition des services</h3>
              <div class="chart-actions">
              </div>
            </div>
            <div class="chart">
              <canvas id="servicesDistribution"></canvas>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <?php include 'assets/php/profileManage.php'; ?>

  <!-- Add this modal markup just before </body> -->
  <div id="meetModal" class="meet-modal" style="display:none;">
    <div class="meet-modal-content">
      <button class="meet-modal-close" id="closeMeetModal" title="Fermer">&times;</button>
      <div id="meetContainer" style="width:100%; height:600px;"></div>
    </div>
  </div>

  <!-- Messagerie Modal -->
<div id="messagerieModal"
  style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:9999; align-items:center; justify-content:center;">
  <div
    style="background:#fff; border-radius:12px; width:400px; max-width:90vw; min-height:300px; box-shadow:0 4px 32px rgba(0,0,0,0.2); position:relative; padding:0;">
    <button id="closeMessagerieModal"
      style="position:absolute; top:8px; right:12px; background:none; border:none; font-size:2rem; color:#888; cursor:pointer;">&times;</button>
    <div style="padding:24px 16px 16px 16px;">
      <h2 style="margin-top:0;">Messagerie</h2>
      <form id="sendMessageForm" style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px;">
        <select id="receiverSelect"
          style="margin-bottom:8px; width:100%; border-radius:6px; border:1px solid #ccc; padding:6px;">
          <!-- Options will be populated dynamically by JS -->
        </select>
        <div style="display:flex; gap:8px;">
          <input type="text" id="messageInput" placeholder="Votre message..."
            style="flex:1; border-radius:6px; border:1px solid #ccc; padding:6px;">
          <button type="submit"
            style="border-radius:6px; background:#1f4f65; color:#fff; border:none; padding:6px 14px;">Envoyer</button>
        </div>
      </form>
      <div id="messagesList"
        style="height:180px; overflow-y:auto; border:1px solid #eee; border-radius:8px; padding:8px; background:#fafbfc;">
      </div>
    </div>
  </div>
</div>

  <script src="assets/js/main.js"></script>
  <script src="assets/js/profile.js"></script>
  <script src="assets/js/profileManage.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="../assets/chatbot/chatbot.js"> </script>
  <script src='https://meet.jit.si/external_api.js'></script>
  <script>
    const CURRENT_USER_ID = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;
  </script>
</body>

</html>