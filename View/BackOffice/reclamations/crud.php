<?php
require_once __DIR__ . '/../../../Controller/ReclamationController.php';

$ReclamationC = new ReclamationController();
$list = $ReclamationC->listReclamation();
$covoiturages = $ReclamationC->getAllCovoiturages();
$clients = $ReclamationC->getAllClients();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Réclamations</title>
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
      background-color: rgba(255, 193, 7, 0.2);
      /* Darkened background */
      color: var(--status-pending);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .status.in-progress {
      background-color: rgba(0, 123, 255, 0.2);
      /* Darkened background */
      color: var(--status-in-progress);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .status.resolved {
      background-color: rgba(40, 167, 69, 0.2);
      /* Darkened background */
      color: var(--status-resolved);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .status.refused {
      background-color: rgba(220, 53, 69, 0.2);
      /* Darkened background */
      color: var(--status-refused);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .status.pending:hover,
    .status.in-progress:hover,
    .status.resolved:hover,
    .status.refused:hover {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      transform: scale(1.03);
      transition: all 0.2s ease;
    }

    .priority {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .priority.low {
      background-color: #e2e3e5;
      color: #383d41;
    }

    .priority.medium {
      background-color: #fff3cd;
      color: #856404;
    }

    .priority.high {
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
      border-left: 4px solid #ff7f07;
    }

    .stat-box.refused {
      border-left: 4px solid #dc3545;
    }

    .complaints-filters {
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

    .complaints-table th,
    .complaints-table td {
      padding: 0.75rem 1rem;
    }

    .complaints-table th {
      background-color: #f8f9fa;
      font-weight: 600;
    }

    .complaints-table tr:hover {
      background-color: #f8f9fa;
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
            <li>
              <a href="../colis/crud.php">
                <i class="fas fa-box"></i>
                <span>Colis</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
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
          <h1>Gestion des Réclamations</h1>
          <p>Consultez et traitez les réclamations des utilisateurs</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher une réclamation...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addRec.php" class="btn primary">
              <i class="fas fa-plus"></i> Ajouter une réclamation
            </a>
          </div>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Stats Overview -->
        <div class="dashboard-stats">
          <div class="stat-box primary">
            <div class="stat-title">Total des réclamations</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
          </div>
          <div class="stat-box success">
            <div class="stat-title">Réclamations résolues</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
          </div>
          <div class="stat-box warning">
            <div class="stat-title">En cours</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-spinner"></i></div>
          </div>
          <div class="stat-box danger">
            <div class="stat-title">En attente</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
          </div>
          <div class="stat-box refused">
            <div class="stat-title">Réclamations refusées</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
          </div>
        </div>


        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-objet="all">Toutes les Réclamations</button>
              <button class="tab-btn" data-objet="Retard">Retard</button>
              <button class="tab-btn" data-objet="Annulation">Annulation</button>
              <button class="tab-btn" data-objet="Dommage">Dommage</button>
              <button class="tab-btn" data-objet="Qualité de service">Qualité de service</button>
              <button class="tab-btn" data-objet="Facturation">Facturation</button>
              <button class="tab-btn" data-objet="Autre">Autre</button>
            </div>
          </div>

          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="complaints-table-container">
              <table class="complaints-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Objet</th>
                    <th>Date</th>
                    <th>Covoiturage</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($list as $rec):
                    // Fetch client and covoiturage details
                    $client = $ReclamationC->getClientById($rec['id_client']);
                    $covoit = $ReclamationC->getCovoiturageById($rec['id_covoit']);
                    ?>
                    <tr>
                      <td><?= $rec['id_rec'] ?></td>
                      <td>
                        <?= htmlspecialchars($client['nom']) ?>   <?= htmlspecialchars($client['prenom']) ?> (ID:
                        <?= htmlspecialchars($client['id_user']) ?>)
                      </td>
                      <td><?= htmlspecialchars($rec['objet']) ?></td>
                      <td><?= htmlspecialchars($rec['date_rec']) ?></td>
                      <td>
                        <?= htmlspecialchars($covoit['lieu_depart']) ?> → <?= htmlspecialchars($covoit['lieu_arrivee']) ?>
                        (ID: <?= htmlspecialchars($covoit['id_covoit']) ?>)
                      </td>
                      <td>
                        <?= strlen($rec['description']) > 50 ? htmlspecialchars(substr($rec['description'], 0, 50)) . '...' : htmlspecialchars($rec['description']) ?>
                      </td>
                      <?php
                      $statusClassMap = [
                        'En attente' => 'pending',
                        'En cours' => 'in-progress',
                        'Résolue' => 'resolved',
                        'Rejetée' => 'refused'
                      ];

                      $statut = trim($rec['statut']);
                      $className = isset($statusClassMap[$statut]) ? $statusClassMap[$statut] : 'default';
                      ?>
                      <td>
                        <span class="status <?= $className ?>">
                          <?= htmlspecialchars($rec['statut']) ?>
                        </span>
                      </td>

                      <td class="actions">
                        <!-- View Button -->
                        <button class="action-btn view" data-id="<?= $rec['id_rec'] ?>"
                          data-client="<?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>"
                          data-objet="<?= htmlspecialchars($rec['objet']) ?>"
                          data-date="<?= htmlspecialchars($rec['date_rec']) ?>"
                          data-covoit="<?= htmlspecialchars($covoit['lieu_depart']) ?> → <?= htmlspecialchars($covoit['lieu_arrivee']) ?>"
                          data-description="<?= htmlspecialchars($rec['description']) ?>"
                          data-statut="<?= htmlspecialchars($rec['statut']) ?>">
                          <i class="fas fa-eye"></i>
                        </button>

                        <!-- Edit Button -->
                        <form method="GET" action="updateRec.php" style="display:inline;">
                          <input type="hidden" name="id_rec" value="<?= $rec['id_rec'] ?>">
                          <button type="submit" class="action-btn edit" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </button>
                        </form>

                        <!-- Delete Button -->
                        <form method="POST" action="deleteRec.php" style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this reclamation?');">
                          <input type="hidden" name="id_rec" value="<?= $rec['id_rec'] ?>">
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

  <div id="viewModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Détails de la Réclamation</h2>
      <p><strong>Client:</strong> <span id="modal-client"></span></p>
      <p><strong>Objet:</strong> <span id="modal-objet"></span></p>
      <p><strong>Date:</strong> <span id="modal-date"></span></p>
      <p><strong>Covoiturage:</strong> <span id="modal-covoit"></span></p>
      <p><strong>Description:</strong></p>
      <p id="modal-description"></p>
      <p><strong>Statut:</strong> <span id="modal-statut"></span></p>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal" id="delete-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible.</p>
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

        // Filter complaints based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });

    // Modal Functions
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');


    // Open Delete Confirmation Modal
    const deleteButtons = document.querySelectorAll('.delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        deleteModal.classList.add('active');
      });
    });

    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
      // Here you would send a delete request to the server
      alert('Réclamation supprimée avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
  <script src="assets/js/recFilters.js"></script>
</body>

</html>