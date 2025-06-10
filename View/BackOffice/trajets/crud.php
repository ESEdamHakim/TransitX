<?php
require_once "../../../Controller/trajetcontroller.php";
require_once __DIR__ . '/../../../Controller/UserC.php';

// Controllers
$trajetController = new TrajetController();

$trajetlist = $trajetController->listTrajets();
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
  <title>TransitX - Gestion des Bus</title>
  <link rel="stylesheet" href="../../assets/css/profile.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
          <link rel="stylesheet" href="../../assets/messagerie/messagerie.css">


</head>

<body>
    <?php include '../../assets/chatbot/chatbot.php'; ?>
          <?php include '../../assets/messagerie/messagerie.php'; ?>


  <div class="dashboard">
    <?php include 'sidebar.php'; ?>
    <!-- Main Content -->
    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Trajets</h1>
          <p>Ajoutez, modifiez et supprimez des trajets</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un trajet par destination" aria-label="Rechercher un trajet">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addtrajet.php" class="btn primary"><i class="fas fa-plus"></i> Ajouter un Trajet</a>
            <div class="actions-container">
              <?php include '../assets/php/profile.php'; ?>
            </div>
          </div>
        </div>
      </header>
      <div class="dashboard-content">
        <div class="dashboard-stats">
          <!-- Total Trajets -->
          <div class="stat-box primary">
            <div class="stat-title">Total Trajets</div>
            <div class="stat-value" id="total-trajets">0</div>
            <div class="stat-icon"><i class="fas fa-road icon"></i></div>
          </div>

          <!-- Durée Moyenne -->
          <div class="stat-box info">
            <div class="stat-title">Durée Moyenne</div>
            <div class="stat-value" id="average-duration">0</div>
            <div class="stat-icon"><i class="fas fa-clock icon"></i></div>
          </div>

          <!-- Prix Moyen -->
          <div class="stat-box success">
            <div class="stat-title">Prix Moyen (TND)</div>
            <div class="stat-value" id="average-price">0</div>
            <div class="stat-icon"><i class="fas fa-money-bill-wave icon"></i></div>
          </div>

          <!-- Distance Moyenne -->
          <div class="stat-box warning">
            <div class="stat-title">Distance Moyenne (km)</div>
            <div class="stat-value" id="average-distance">0</div>
            <div class="stat-icon"><i class="fas fa-ruler icon"></i></div>
          </div>
        </div>
      </div>
      <div class="crud-container">
        <div class="view-container table-view active">
          <div class="buses-table-container">
            <table class="buses-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Départ</th>
                  <th>Arrivée</th>
                  <th>Heure</th>
                  <th>Durée</th>
                  <th>Distance (km)</th>
                  <th>Prix (TND)</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($trajetlist as $trajet): ?>
                  <tr>
                    <?php foreach (['id_trajet', 'place_depart', 'place_arrivee', 'heure_depart', 'duree', 'distance_km', 'prix'] as $field): ?>
                      <td><?= htmlspecialchars($trajet[$field]) ?></td>
                    <?php endforeach; ?>
                    <td class="actions">
                      <form method="GET" action="updatetrajet.php" style="display:inline;">
                        <input type="hidden" name="id_trajet" value="<?= htmlspecialchars($trajet['id_trajet']) ?>">
                        <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      </form>
                      <button type="button" class="action-btn delete open-delete-modal" title="Supprimer"
                        data-id="<?= htmlspecialchars($trajet['id_trajet']) ?>">
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
    </main>
  </div>
  <div class="modal" id="delete-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce bus ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hidden Delete Form -->
  <form method="POST" action="deletetrajet.php" style="display:none;" id="delete-form">
    <input type="hidden" name="id_trajet" id="delete-id">
  </form>
  <?php include '../assets/php/profileManage.php'; ?>
  <script src="assets/js/main.js"></script>
  <script src="../assets/js/profile.js"></script>
  <script src="assets/js/profileManage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>
    <script src="../../assets/messagerie/messagerie.js"> </script>


</body>

</html>