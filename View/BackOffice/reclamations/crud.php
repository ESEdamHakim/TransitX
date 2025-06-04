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
  <link rel="stylesheet" href="assets/css/reclamation.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
  <div class="dashboard">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Réclamations</h1>
          <p>Consultez et traitez les réclamations des utilisateurs</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Nom du client">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="PDF.php" target="_blank" class="btn secondary">
              <i class="fas fa-download"></i> Exporter
            </a>
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
                        <?= htmlspecialchars($client['id']) ?>)
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
                        <button type="button" class="action-btn delete open-delete-modal" title="Supprimer"
                          data-id="<?= htmlspecialchars($rec['id_rec']) ?>"
                          data-nom="<?= htmlspecialchars($client['nom']) ?>"
                          data-prenom="<?= htmlspecialchars($client['prenom']) ?>">
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

  <!-- View Modal -->
  <div id="viewModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Détails de la Réclamation</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="bus-info">
          <p><strong>Client:</strong> <span id="modal-client"></span></p>
          <p><strong>Objet:</strong> <span id="modal-objet"></span></p>
          <p><strong>Date:</strong> <span id="modal-date"></span></p>
          <p><strong>Covoiturage:</strong> <span id="modal-covoit"></span></p>
          <p><strong>Description:</strong></p>
          <p id="modal-description"></p>
          <p><strong>Statut:</strong> <span id="modal-statut"></span></p>
        </div>
      </div>
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

  <!-- Hidden Delete Form -->
  <form method="POST" action="deleteRec.php" style="display:none;" id="delete-form">
    <input type="hidden" name="id_rec" id="delete-id">
  </form>
  <script>
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
  </script>
  <script src="assets/js/recFilters.js"></script>
</body>

</html>