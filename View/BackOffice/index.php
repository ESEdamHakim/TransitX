<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Dashboard</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
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
         <a href="../FrontOffice/index.php" class="logo-link">
          <div class="logo">
            <img src="../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
            <span>Transit</span><span class="highlight">X</span>
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
          <a href="../../../index.php" class="logout">
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
          <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <button class="notification-btn">
            <i class="fas fa-bell"></i>
            <span class="badge">3</span>
          </button>
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

        <!-- Reports Section -->
        <div class="reports-section">
          <div class="reports-header">
            <h3>Rapports</h3>
            <button class="btn primary">
              <i class="fas fa-plus"></i> Nouveau rapport
            </button>
          </div>

          <div class="reports-tabs">
            <button class="report-tab active" data-report="financial">Financier</button>
            <button class="report-tab" data-report="operations">Opérations</button>
            <button class="report-tab" data-report="users">Utilisateurs</button>
          </div>

          <div class="report-content active" id="financial-report">
            <div class="report-summary">
              <div class="summary-item">
                <div class="value">12,500 TND</div>
                <div class="label">Revenus</div>
              </div>
              <div class="summary-item">
                <div class="value">8,200 TND</div>
                <div class="label">Dépenses</div>
              </div>
              <div class="summary-item">
                <div class="value">4,300 TND</div>
                <div class="label">Profit</div>
              </div>
              <div class="summary-item">
                <div class="value">34.4%</div>
                <div class="label">Marge</div>
              </div>
            </div>

            <table class="report-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Service</th>
                  <th>Client</th>
                  <th>Montant</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>15/04/2023</td>
                  <td>Covoiturage</td>
                  <td>Ahmed Ben Ali</td>
                  <td>25.00 TND</td>
                  <td><span class="status delivered">Complété</span></td>
                </tr>
                <tr>
                  <td>14/04/2023</td>
                  <td>Colis</td>
                  <td>Leila Mansour</td>
                  <td>18.50 TND</td>
                  <td><span class="status delivered">Complété</span></td>
                </tr>
                <tr>
                  <td>13/04/2023</td>
                  <td>Bus</td>
                  <td>Mohamed Khelifi</td>
                  <td>12.00 TND</td>
                  <td><span class="status delivered">Complété</span></td>
                </tr>
              </tbody>
            </table>

            <div class="report-actions">
              <button class="btn-outline"><i class="fas fa-download"></i> Télécharger PDF</button>
              <button class="btn-outline"><i class="fas fa-file-excel"></i> Exporter Excel</button>
              <button class="btn-outline"><i class="fas fa-print"></i> Imprimer</button>
              <button class="btn-outline"><i class="fas fa-envelope"></i> Envoyer par email</button>
            </div>
          </div>

          <div class="report-content" id="operations-report">
            <div class="report-summary">
              <div class="summary-item">
                <div class="value">856</div>
                <div class="label">Trajets</div>
              </div>
              <div class="summary-item">
                <div class="value">128</div>
                <div class="label">Colis</div>
              </div>
              <div class="summary-item">
                <div class="value">24</div>
                <div class="label">Bus</div>
              </div>
              <div class="summary-item">
                <div class="value">92%</div>
                <div class="label">Satisfaction</div>
              </div>
            </div>

            <table class="report-table">
              <thead>
                <tr>
                  <th>Service</th>
                  <th>Nombre</th>
                  <th>Croissance</th>
                  <th>Taux de complétion</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Covoiturage</td>
                  <td>856</td>
                  <td>+8.2%</td>
                  <td>95%</td>
                </tr>
                <tr>
                  <td>Colis</td>
                  <td>128</td>
                  <td>-3.1%</td>
                  <td>87%</td>
                </tr>
                <tr>
                  <td>Bus</td>
                  <td>24</td>
                  <td>+12.5%</td>
                  <td>98%</td>
                </tr>
              </tbody>
            </table>

            <div class="report-actions">
              <button class="btn-outline"><i class="fas fa-download"></i> Télécharger PDF</button>
              <button class="btn-outline"><i class="fas fa-file-excel"></i> Exporter Excel</button>
              <button class="btn-outline"><i class="fas fa-print"></i> Imprimer</button>
            </div>
          </div>

          <div class="report-content" id="users-report">
            <div class="report-summary">
              <div class="summary-item">
                <div class="value">1,423</div>
                <div class="label">Total Utilisateurs</div>
              </div>
              <div class="summary-item">
                <div class="value">180</div>
                <div class="label">Nouveaux</div>
              </div>
              <div class="summary-item">
                <div class="value">65%</div>
                <div class="label">Actifs</div>
              </div>
              <div class="summary-item">
                <div class="value">4.2</div>
                <div class="label">Note moyenne</div>
              </div>
            </div>

            <table class="report-table">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Nombre</th>
                  <th>Croissance</th>
                  <th>Taux d'activité</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Clients</td>
                  <td>1,245</td>
                  <td>+15.2%</td>
                  <td>62%</td>
                </tr>
                <tr>
                  <td>Conducteurs</td>
                  <td>156</td>
                  <td>+8.7%</td>
                  <td>85%</td>
                </tr>
                <tr>
                  <td>Administrateurs</td>
                  <td>22</td>
                  <td>+4.5%</td>
                  <td>95%</td>
                </tr>
              </tbody>
            </table>

            <div class="report-actions">
              <button class="btn-outline"><i class="fas fa-download"></i> Télécharger PDF</button>
              <button class="btn-outline"><i class="fas fa-file-excel"></i> Exporter Excel</button>
              <button class="btn-outline"><i class="fas fa-print"></i> Imprimer</button>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
          <div class="section-header">
            <h3>Activité récente</h3>
            <a href="#" class="view-all">Voir tout</a>
          </div>

          <div class="activity-list">
            <div class="activity-item">
              <div class="activity-icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <div class="activity-details">
                <h4>Nouvel utilisateur inscrit</h4>
                <p>Ahmed Ben Ali a créé un compte.</p>
                <span class="activity-time">Il y a 2 heures</span>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">
                <i class="fas fa-car-side"></i>
              </div>
              <div class="activity-details">
                <h4>Nouveau trajet de covoiturage</h4>
                <p>Leila Mansour a publié un trajet Tunis → Sousse.</p>
                <span class="activity-time">Il y a 3 heures</span>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">
                <i class="fas fa-box"></i>
              </div>
              <div class="activity-details">
                <h4>Nouveau colis enregistré</h4>
                <p>Mohamed Khelifi a enregistré un colis pour livraison.</p>
                <span class="activity-time">Il y a 5 heures</span>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-icon">
                <i class="fas fa-exclamation-circle"></i>
              </div>
              <div class="activity-details">
                <h4>Nouvelle réclamation</h4>
                <p>Nadia Mansouri a signalé un problème avec un colis.</p>
                <span class="activity-time">Il y a 6 heures</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Parcels -->
        <div class="recent-parcels">
          <div class="section-header">
            <h3>Colis récents</h3>
            <a href="colis/crud.php" class="view-all">Voir tout</a>
          </div>

          <div class="parcels-table-container">
            <table class="parcels-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Expéditeur</th>
                  <th>Destinataire</th>
                  <th>Destination</th>
                  <th>Date</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>P001</td>
                  <td>Ahmed Ben Ali</td>
                  <td>Sami Trabelsi</td>
                  <td>Tunis</td>
                  <td>20/04/2023</td>
                  <td><span class="status in-transit">En transit</span></td>
                  <td class="actions">
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>P002</td>
                  <td>Leila Mansour</td>
                  <td>Karim Belhaj</td>
                  <td>Sousse</td>
                  <td>19/04/2023</td>
                  <td><span class="status delivered">Livré</span></td>
                  <td class="actions">
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>P003</td>
                  <td>Mohamed Khelifi</td>
                  <td>Nadia Mansouri</td>
                  <td>Sfax</td>
                  <td>20/04/2023</td>
                  <td><span class="status pending">En attente</span></td>
                  <td class="actions">
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>P004</td>
                  <td>Youssef Mejri</td>
                  <td>Fatma Riahi</td>
                  <td>Bizerte</td>
                  <td>18/04/2023</td>
                  <td><span class="status cancelled">Annulé</span></td>
                  <td class="actions">
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>

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
</body>

</html>