<?php
require_once __DIR__ . '/../../../Controller/ColisController.php';

$ColisC = new ColisController();
$list = $ColisC->listColis();
$covoiturages = $ColisC->getAllCovoiturages();
$clients = $ColisC->getAllClients();
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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

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
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-box"></i></div>
          </div>
          <div class="stat-box success">
            <div class="stat-title">Colis livrés</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
          </div>
          <div class="stat-box warning">
            <div class="stat-title">En transit</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-truck"></i></div>
          </div>
          <div class="stat-box danger">
            <div class="stat-title">En attente</div>
            <div class="stat-value">0</div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
          </div>
        </div>

        <!-- Filters -->
        <div class="colis-filters">
          <div class="filter-item">
            <label for="status-filter">Statut</label>
            <select id="status-filter">
              <option value="all">Tous</option>
              <option value="pending">En attente</option>
              <option value="in-progress">En transit</option>
              <option value="resolved">Livrés</option>
            </select>
          </div>
          <div class="filter-item">
            <label for="date-filter">Date</label>
            <input type="date" id="date-filter">
          </div>
          <div class="filter-item">
            <label for="search-filter">Recherche</label>
            <input type="text" id="search-filter" placeholder="ID covoiturage">
          </div>
          <div class="filter-item">
            <label for="price-sort">Tri par prix</label>
            <select id="price-sort">
              <option value="none">Aucun tri</option>
              <option value="asc">Prix croissant</option>
              <option value="desc">Prix décroissant</option>
            </select>
          </div>
          <button id="apply-filters" class="btn primary">Appliquer</button>
          <button id="reset-filters" class="btn secondary">Réinitialiser</button>
        </div>

        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Colis</button>
              <button class="tab-btn" data-tab="pending">En attente</button>
              <button class="tab-btn" data-tab="in-progress">En transit</button>
              <button class="tab-btn" data-tab="resolved">Livrés</button>
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
                    <th>Date d'envoi</th>
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
                  <?php foreach ($list as $colis):
                    $client = $ColisC->getClientById($colis['id_client']);
                    $covoit = !empty($colis['id_covoit']) ? $ColisC->getCovoiturageById($colis['id_covoit']) : null;

                    $statusClassMap = [
                      'en attente' => 'pending',
                      'en transit' => 'in-progress',
                      'livré' => 'resolved',
                      'annulé' => 'cancelled'
                    ];

                    $statut = trim($colis['statut']);
                    $className = $statusClassMap[$statut] ?? 'default';
                    ?>
                    <tr class="parcel-row" data-status="<?= $className ?>"
                      data-date="<?= htmlspecialchars($colis['date_colis']) ?>"
                      data-price="<?= htmlspecialchars($colis['prix']) ?>"
                      data-covoit-id="<?= $covoit ? htmlspecialchars($covoit['id_covoit']) : '' ?>">

                      <td><?= $colis['id_colis'] ?></td>

                      <td>
                        <?= htmlspecialchars($client['nom']) ?>   <?= htmlspecialchars($client['prenom']) ?>
                        <small class="muted">(ID: <?= htmlspecialchars($client['id_user']) ?>)</small>
                      </td>

                      <td>
                        <?php if ($covoit): ?>
                          <?= htmlspecialchars($covoit['lieu_depart']) ?> → <?= htmlspecialchars($covoit['lieu_arrivee']) ?>
                          <small class="muted">(ID: <?= htmlspecialchars($covoit['id_covoit']) ?>)</small>
                        <?php else: ?>
                          <em class="text-muted">Aucun covoiturage</em>
                        <?php endif; ?>
                      </td>

                      <td><?= htmlspecialchars($colis['date_colis']) ?></td>

                      <td>
                        <?= number_format($colis['longueur'], 2) ?> ×
                        <?= number_format($colis['largeur'], 2) ?> ×
                        <?= number_format($colis['hauteur'], 2) ?> cm
                      </td>

                      <td><?= number_format($colis['poids'], 2) ?> kg</td>
                      <td><?= htmlspecialchars($colis['lieu_ram']) ?></td>
                      <td><?= htmlspecialchars($colis['lieu_dest']) ?></td>

                      <td>
                        <span class="status <?= $className ?>">
                          <?= htmlspecialchars($colis['statut']) ?>
                        </span>
                      </td>

                      <td><?= number_format($colis['prix'], 2) ?> DT</td>

                      <td class="actions">
                        <form method="GET" action="updateColis.php" class="inline-form">
                          <input type="hidden" name="id_colis" value="<?= $colis['id_colis'] ?>">
                          <button type="submit" class="action-btn edit" title="Modifier le colis">
                            <i class="fas fa-edit"></i>
                          </button>
                        </form>

                        <button type="button" class="action-btn delete open-delete-modal" title="Supprimer le colis"
                          data-id="<?= htmlspecialchars($colis['id_colis']) ?>">
                          <i class="fas fa-trash"></i>
                        </button>
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

  <!-- Hidden Delete Form -->
  <form method="POST" action="deleteColis.php" style="display:none;" id="delete-form">
    <input type="hidden" name="id_colis" id="delete-id">
  </form>


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
  </script>
  <script src="assets/js/colisDelete.js"></script>
  <script src="assets/js/colisFilters.js" defer></script>

</body>

</html>