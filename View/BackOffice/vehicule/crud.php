<?php
// Include the display logic
include 'BdisplayVehicule.php';
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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Véhicules</title>
  <link rel="stylesheet" href="../../assets/css/profile.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
          <link rel="stylesheet" href="../../assets/messagerie/messagerie.css">


  <style>
    .header-left h1 {
      color: #1f4f65;
      /* Blue color for the title */
    }
  </style>
</head>

<body>
    <?php include '../../assets/chatbot/chatbot.php'; ?>
          <?php include '../../assets/messagerie/messagerie.php'; ?>


  <!--the dashboard +color logo..-->
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <a href="../../FrontOffice/index.php" class="logo-link">
          <div class="logoback">
            <img src="../../assets/images/logo.png" alt="TransitX Logoback" class="nav-logoback">
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
            <li><a href="../trajets/crud.php"><i class="fas fa-road"></i><span>Trajets</span></a></li>

            <li>
              <a href="../colis/crud.php">
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
            <li class="active">
              <a href="crud.php">
                <i class="fas fa-car"></i>
                <span>Véhicules</span>
              </a>
            </li>
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
          <h1>Gestion des Véhicules</h1>
          <p>Ajoutez, modifiez et supprimez des véhicules</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un véhicule...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <button id="add-vehicule-btn" style="display: none;">
              <i class="fas fa-plus"></i> Ajouter un Véhicule
            </button>
            <div class="actions-container">
              <?php include '../assets/php/profile.php'; ?>
            </div>
          </div>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Véhicules</button>
              <button class="tab-btn" data-tab="active">Actifs</button>
              <button class="tab-btn" data-tab="pending">En attente</button>
              <button class="tab-btn" data-tab="completed">Terminés</button>
            </div>
          </div>

          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="rides-table-container">
              <table class="rides-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Matricule</th>
                    <th>Type</th>
                    <th>Nombre de Places</th>
                    <th>Couleur</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Confort</th>
                    <th>Photo</th>
                    <th>Utilisateur</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($vehicules as $vehicule): ?>
                    <tr>
                      <td><?= htmlspecialchars($vehicule['id_vehicule']) ?></td>
                      <td><?= htmlspecialchars($vehicule['matricule']) ?></td>
                      <td><?= htmlspecialchars($vehicule['type_vehicule']) ?></td>
                      <td><?= htmlspecialchars($vehicule['nb_places']) ?></td>
                      <td><?= htmlspecialchars($vehicule['couleur']) ?></td>
                      <td><?= htmlspecialchars($vehicule['marque']) ?></td>
                      <td><?= htmlspecialchars($vehicule['modele']) ?></td>
                      <td><?= htmlspecialchars($vehicule['confort']) ?></td>

                      <td>
                        <?php if (!empty($vehicule['photo_vehicule'])): ?>
                          <img src="../../assets/uploads/<?= htmlspecialchars($vehicule['photo_vehicule']) ?>"
                            alt="Photo du véhicule" width="50">
                        <?php else: ?>
                          Pas de photo
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($vehicule['user_name'] ?? 'Utilisateur inconnu') ?></td>
                      <td class="actions">
                        <button class="action-btn edit" data-id="<?= $vehicule['id_vehicule'] ?>"><i
                            class="fas fa-edit"></i></button>
                        <button class="action-btn delete" data-id="<?= $vehicule['id_vehicule'] ?>"><i
                            class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <script src="searchvehicule.js"></script>
          </div>

          <!-- Grid View -->
          <div class="view-container grid-view">
            <div class="rides-grid">
              <!-- Grid View empty -->
            </div>
            <div class="pagination">
              <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
              <button class="pagination-btn active">1</button>
              <button class="pagination-btn">2</button>
              <button class="pagination-btn">3</button>
              <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal for Adding/Editing Vehicle -->
  <div class="modal" id="vehicle-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modal-title">Ajouter un Véhicule</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form id="vehicle-form" method="POST" action="updateVehicule.php">
          <input type="hidden" id="id_vehicule" name="id_vehicule">
          <input type="hidden" id="existing-photo" name="existing_photo">

          <div class="form-group">

            <label for="vehicle-matricule">Matricule</label>
            <input type="text" id="vehicle-matricule" name="matricule">
            <span id="vehicle-matricule-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="vehicle-type">Type</label>
            <input type="text" id="vehicle-type" name="type_vehicule">
            <span id="vehicle-type-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="vehicle-seats">Nombre de Places</label>
            <input type="number" id="vehicle-seats" name="nb_places">
            <span id="vehicle-seats-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="vehicle-color">Couleur</label>
            <input type="text" id="vehicle-color" name="couleur">
            <span id="vehicle-color-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="vehicle-brand">Marque</label>
            <input type="text" id="vehicle-brand" name="marque">
            <span id="vehicle-brand-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="vehicle-model">Modèle</label>
            <input type="text" id="vehicle-model" name="modele">
            <span id="vehicle-model-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="vehicle-comfort">Confort</label>
            <input type="text" id="vehicle-comfort" name="confort">
            <span id="vehicle-comfort-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-photo">Photo</label>
            <input type="file" id="ride-photo" name="photo_vehicule">
            <span id="ride-photo-error" class="error-message"></span>
          </div>
          <div class="form-actions">
            <button type="button" class="btn secondary cancel-btn">Annuler</button>
            <button type="submit" class="btn primary">Enregistrer</button>
          </div>
        </form>
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
        <p>Êtes-vous sûr de vouloir supprimer ce véhicule ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
  <?php include '../assets/php/profileManage.php'; ?>

  <script src="BvalidVehicule.js"></script>
  <script src="../assets/js/profile.js"></script>
  <script src="assets/js/profileManage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>
    <script src="../../assets/messagerie/messagerie.js"> </script>

</body>

</html>