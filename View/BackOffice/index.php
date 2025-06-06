<?php
require_once __DIR__ . '/../../Controller/UserC.php';

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
              <h3>1,423</h3>
              <p>Utilisateurs</p>
              <div class="stat-progress">
                <i class="fas fa-arrow-up"></i>
                <span>+12.5%</span>
              </div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon rides">
              <i class="fas fa-car-side"></i>
            </div>
            <div class="stat-details">
              <h3>856</h3>
              <p>Trajets</p>
              <div class="stat-progress">
                <i class="fas fa-arrow-up"></i>
                <span>+8.2%</span>
              </div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon parcels">
              <i class="fas fa-box"></i>
            </div>
            <div class="stat-details">
              <h3>128</h3>
              <p>Colis</p>
              <div class="stat-progress negative">
                <i class="fas fa-arrow-down"></i>
                <span>-3.1%</span>
              </div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon complaints">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-details">
              <h3>24</h3>
              <p>Réclamations</p>
              <div class="stat-progress">
                <i class="fas fa-arrow-up"></i>
                <span>+5.7%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
          <div class="chart-container">
            <div class="chart-header">
              <h3>Statistiques mensuelles</h3>
              <div class="chart-actions">
                <select>
                  <option>Dernier mois</option>
                  <option>Derniers 3 mois</option>
                  <option>Derniers 6 mois</option>
                  <option>Dernière année</option>
                </select>
              </div>
            </div>
            <div class="chart">
              <canvas id="monthlyStats"></canvas>
            </div>
          </div>

          <div class="chart-container">
            <div class="chart-header">
              <h3>Répartition des services</h3>
              <div class="chart-actions">
                <select>
                  <option>Tous</option>
                  <option>Bus</option>
                  <option>Covoiturage</option>
                  <option>Colis</option>
                </select>
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
  <script>
    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function () {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });

    // Report Tabs
    const reportTabs = document.querySelectorAll('.report-tab');
    const reportContents = document.querySelectorAll('.report-content');

    reportTabs.forEach(tab => {
      tab.addEventListener('click', function () {
        // Remove active class from all tabs and contents
        reportTabs.forEach(t => t.classList.remove('active'));
        reportContents.forEach(c => c.classList.remove('active'));

        // Add active class to clicked tab
        this.classList.add('active');

        // Show corresponding content
        const reportType = this.getAttribute('data-report');
        document.getElementById(`${reportType}-report`).classList.add('active');
      });
    });

    // Charts
    const monthlyStatsCtx = document.getElementById('monthlyStats').getContext('2d');
    const monthlyStatsChart = new Chart(monthlyStatsCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil'],
        datasets: [
          {
            label: 'Utilisateurs',
            data: [650, 730, 810, 890, 950, 1020, 1150],
            borderColor: '#17a2b8',
            backgroundColor: 'rgba(23, 162, 184, 0.1)',
            fill: true,
            tension: 0.4
          },
          {
            label: 'Trajets',
            data: [320, 380, 420, 480, 520, 580, 650],
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true,
            tension: 0.4
          },
          {
            label: 'Colis',
            data: [85, 95, 105, 115, 125, 120, 128],
            borderColor: '#ffc107',
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
            fill: true,
            tension: 0.4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    const servicesDistributionCtx = document.getElementById('servicesDistribution').getContext('2d');
    const servicesDistributionChart = new Chart(servicesDistributionCtx, {
      type: 'doughnut',
      data: {
        labels: ['Bus', 'Covoiturage', 'Colis'],
        datasets: [{
          data: [35, 45, 20],
          backgroundColor: ['#1f4f65', '#97c3a2', '#d7dd83'],
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    });
  </script>
  <script src="assets/js/profile.js"></script>
  <script src="assets/js/profileManage.js"></script>
</body>

</html>