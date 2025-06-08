<?php
require_once __DIR__ . '/../../../Controller/ColisController.php';
require_once __DIR__ . '/../../../Controller/UserC.php';

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
$covoiturages = $ColisC->getAllCovoiturages();
$clients = $ColisC->getAllClients();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Colis</title>
  <link rel="stylesheet" href="../../assets/css/profile.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">


</head>

<body>
    <?php include '../../assets/chatbot/chatbot.php'; ?>

  <div class="dashboard">
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Colis</h1>
          <p>Ajoutez, modifiez et supprimez des colis</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" id="search-filter" placeholder="Nom du client">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addColis.php" class="btn primary">
              <i class="fas fa-plus"></i> Ajouter un Colis
            </a>
            <div class="actions-container">
              <?php include '../assets/php/profile.php'; ?>
            </div>
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
                    $covoit = null;
                    $client2 = null;

                    // Fetch covoiturage and client only if id_covoit is not empty
                    if (!empty($colis['id_covoit'])) {
                      $covoit = $ColisC->getCovoiturageById($colis['id_covoit']);

                      if ($covoit && isset($covoit['id_user'])) {
                        $client2 = $ColisC->getClientById($covoit['id_user']);
                      }
                    }
                    $statusClassMap = [
                      'en attente' => 'pending',
                      'en transit' => 'in-progress',
                      'livré' => 'resolved',
                    ];

                    $statut = trim($colis['statut']);
                    $className = $statusClassMap[$statut] ?? 'default';
                    ?>
                    <tr class="parcel-row" data-status="<?= $className ?>"
                      data-client-name="<?= strtolower(htmlspecialchars($client['nom'] . ' ' . $client['prenom'])) ?>"
                      data-date="<?= htmlspecialchars($colis['date_colis']) ?>">

                      <td><?= $colis['id_colis'] ?></td>

                      <td>
                        <?= htmlspecialchars($client['nom']) ?>   <?= htmlspecialchars($client['prenom']) ?>
                        <small class="muted">(ID: <?= htmlspecialchars($client['id']) ?>)</small>
                      </td>

                      <td>
                        <?php if ($covoit): ?>
                          <?= htmlspecialchars($client2['nom']) ?>     <?= htmlspecialchars($client2['prenom']) ?>
                          <small class="muted">(ID: <?= htmlspecialchars($client2['id']) ?>)</small>
                        <?php else: ?>
                          <em>Aucun covoiturage</em>
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

  <?php include '../assets/php/profileManage.php'; ?>
  <script>
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
  <script src="../assets/js/profile.js"></script>
  <script src="assets/js/profileManage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>

</body>

</html>