<?php
require_once __DIR__ . '/../../../Controller/ColisController.php';

$ColisC = new ColisController();
$list = $ColisC->listColis();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Colis</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .status {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .status.pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .status.in-transit {
      background-color: #cce5ff;
      color: #004085;
    }

    .status.delivered {
      background-color: #d4edda;
      color: #155724;
    }

    .status.cancelled {
      background-color: #f8d7da;
      color: #721c24;
    }

    .dashboard-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-box {
      background-color: #fff;
      border-radius: 8px;
      padding: 1.25rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
    }

    .stat-box .stat-title {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }

    .stat-box .stat-value {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .stat-box .stat-icon {
      align-self: flex-end;
      margin-top: -2.5rem;
      font-size: 1.5rem;
      opacity: 0.2;
    }

    .stat-box.primary {
      border-left: 4px solid #1f4f65;
    }

    .stat-box.success {
      border-left: 4px solid #28a745;
    }

    .stat-box.warning {
      border-left: 4px solid #ffc107;
    }

    .stat-box.danger {
      border-left: 4px solid #dc3545;
    }

    .colis-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 1.5rem;
      background-color: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
    }

    .filter-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .filter-item label {
      font-weight: 500;
      font-size: 0.9rem;
    }

    .filter-item select,
    .filter-item input {
      padding: 0.5rem;
      border: 1px solid #ced4da;
      border-radius: 4px;
    }

    .filter-actions {
      margin-left: auto;
    }

    .action-btn {
      width: 32px;
      height: 32px;
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
        <button class="sidebar-toggle">
          <i class="fas fa-bars"></i>
        </button>
      </div>

      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li>
              <a href="../index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="../users/crud.php">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
              </a>
            </li>
            <li>
              <a href="../bus/crud.php">
                <i class="fas fa-bus"></i>
                <span>Bus</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
                <i class="fas fa-box"></i>
                <span>Colis</span>
              </a>
            </li>
            <li>
              <a href="../reclamations/crud.php">
                <i class="fas fa-exclamation-circle"></i>
                <span>Réclamations</span>
              </a>
            </li>
            <li>
              <a href="../covoiturage/crud.php">
                <i class="fas fa-car-side"></i>
                <span>Covoiturage</span>
              </a>
            </li>
            <li>
              <a href="../blog/crud.php">
                <i class="fas fa-blog"></i>
                <span>Blog</span>
              </a>
            </li>
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
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </a>
      </div>
    </aside>

    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Colis</h1>
          <p>Ajoutez, modifiez et supprimez des colis</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un colis...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addColis.php" class="btn primary">
              <i class="fas fa-plus"></i> Ajouter un Colis
            </a>
          </div>

        </div>
      </header>

      <div class="dashboard-content">
        <!-- Stats Overview -->
        <div class="dashboard-stats">
          <div class="stat-box primary">
            <div class="stat-title">Total des colis</div>
            <div class="stat-value">128</div>
            <div class="stat-icon"><i class="fas fa-box"></i></div>
          </div>
          <div class="stat-box success">
            <div class="stat-title">Colis livrés</div>
            <div class="stat-value">76</div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
          </div>
          <div class="stat-box warning">
            <div class="stat-title">En transit</div>
            <div class="stat-value">42</div>
            <div class="stat-icon"><i class="fas fa-truck"></i></div>
          </div>
          <div class="stat-box danger">
            <div class="stat-title">En attente</div>
            <div class="stat-value">10</div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
          </div>
        </div>

        <!-- Filters -->
        <div class="colis-filters">
          <div class="filter-item">
            <label for="status-filter">Statut:</label>
            <select id="status-filter">
              <option value="all">Tous</option>
              <option value="pending">En attente</option>
              <option value="in-transit">En transit</option>
              <option value="delivered">Livrés</option>
              <option value="cancelled">Annulés</option>
            </select>
          </div>
          <div class="filter-item">
            <label for="date-filter">Date:</label>
            <input type="date" id="date-filter">
          </div>
          <div class="filter-item">
            <label for="city-filter">Ville:</label>
            <select id="city-filter">
              <option value="all">Toutes</option>
              <option value="tunis">Tunis</option>
              <option value="sousse">Sousse</option>
              <option value="sfax">Sfax</option>
              <option value="monastir">Monastir</option>
            </select>
            <button class="btn primary">Appliquer</button>
            <button class="btn secondary">Réinitialiser</button>
          </div>
        </div>

        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Colis</button>
              <button class="tab-btn" data-tab="pending">En attente</button>
              <button class="tab-btn" data-tab="transit">En transit</button>
              <button class="tab-btn" data-tab="delivered">Livrés</button>
            </div>
          </div>

          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="colis-table-container">
              <table class="parcels-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Covoit</th>
                    <th>Date.d'envoi</th>
                    <th>Dimensions (L × l × H)</th>
                    <th>Poids</th>
                    <th>Lieu Ram</th>
                    <th>Lieu Dest</th>
                    <th>Statut</th>
                    <th>Prix</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($list as $colis): ?>
                    <tr>
                      <td><?= $colis['id_colis'] ?></td>
                      <td><?= htmlspecialchars($colis['id_client']) ?></td>
                      <td><?= htmlspecialchars($colis['id_covoit']) ?></td>
                      <td><?= htmlspecialchars($colis['date_colis']) ?></td>
                      <td>
                        <?= number_format($colis['longueur'], 2) ?> ×
                        <?= number_format($colis['largeur'], 2) ?> ×
                        <?= number_format($colis['hauteur'], 2) ?> cm
                      </td>
                      <td><?= number_format($colis['poids'], 2) ?> kg</td>
                      <td><?= htmlspecialchars($colis['lieu_ram']) ?></td>
                      <td><?= htmlspecialchars($colis['lieu_dest']) ?></td>
                      <td><?= htmlspecialchars($colis['statut']) ?></td>
                      <td><?= htmlspecialchars($colis['prix']) ?> DT</td>
                      <td class="actions">
                        <form method="GET" action="updateColis.php" style="display:inline;">
                          <input type="hidden" name="id_colis" value="<?= $colis['id_colis'] ?>">
                          <button type="submit" class="action-btn edit" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </button>
                        </form>

                        <form method="POST" action="deleteColis.php" style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this colis?');">
                          <input type="hidden" name="id_colis" value="<?= $colis['id_colis'] ?>">
                          <button type="submit" class="action-btn delete" title="Supprimer">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal" id="delete-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function () {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });

    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        // Filter colis based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });

    // Modal Functions
    const colisModal = document.getElementById('colis-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');

    // Open Add Colis Modal
    document.getElementById('add-colis-btn').addEventListener('click', function () {
      document.getElementById('modal-title').textContent = 'Ajouter un Colis';
      document.getElementById('colis-form').reset();
      colisModal.classList.add('active');
    });

    // Open Edit Colis Modal
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function () {
        document.getElementById('modal-title').textContent = 'Modifier un Colis';
        // Here you would populate the form with the colis data
        colisModal.classList.add('active');
      });
    });

    // Open Delete Confirmation Modal
    const deleteButtons = document.querySelectorAll('.delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        deleteModal.classList.add('active');
      });
    });

    // Close Modals
    closeButtons.forEach(button => {
      button.addEventListener('click', function () {
        colisModal.classList.remove('active');
        deleteModal.classList.remove('active');
      });
    });

    // Form Submit Handler (would normally use AJAX)
    document.getElementById('colis-form').addEventListener('submit', function (e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Colis enregistré avec succès!');
      colisModal.classList.remove('active');
    });

    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
      // Here you would send a delete request to the server
      alert('Colis supprimé avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
</body>

</html>