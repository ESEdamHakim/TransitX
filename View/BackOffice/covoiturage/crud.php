<?php
// Include the display logic
include 'Bdisplaycovoiturage.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion du Covoiturage</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .header-left h1 {
      color: #1f4f65;
      /* Blue color for the title */
    }
  </style>
</head>

<body>
  <!--the dashboard +color logo..-->
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <a href="../../FrontOffice/index.php" class="logo-link">
          <div class="logo">
            <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
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
            <li class="active">
              <a href="crud.php">
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
            <li>
              <a href="../vehicule/crud.php">
                <i class="fas fa-car"></i>
                <span>Véhicules</span>
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
          <h1>Gestion du Covoiturage</h1>
          <p>Ajoutez, modifiez et supprimez des trajets de covoiturage</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un trajet...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <button id="add-covoiturage-btn" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un Trajet
          </button>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Trajets</button>
              <button class="tab-btn" data-tab="active">Récents</button>
              <button class="tab-btn" data-tab="pending">Anciens</button>
            </div>
            <div class="view-options">
              <button class="view-btn active" data-view="table"><i class="fas fa-list"></i></button>
              <button class="view-btn" data-view="grid"><i class="fas fa-th"></i></button>
            </div>
          </div>

          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="rides-table-container">
              <table class="rides-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Départ</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places disponibles</th>
                    <th>Prix</th>
                    <th>Colis</th>
                    <th>Détails</th>
                    <th>Utilisateur</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($covoiturages as $covoiturage): ?>
                    <tr>
                      <td><?= htmlspecialchars($covoiturage['id_covoit']) ?></td>
                      <td><?= htmlspecialchars($covoiturage['lieu_depart']) ?></td>
                      <td><?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></td>
                      <td><?= htmlspecialchars($covoiturage['date_depart']) ?></td>
                      <td><?= htmlspecialchars($covoiturage['temps_depart']) ?></td>
                      <td><?= htmlspecialchars($covoiturage['places_dispo']) ?></td>
                      <td><?= htmlspecialchars($covoiturage['prix']) ?> TND</td>
                      <td><?= $covoiturage['accepte_colis'] ? 'Oui' : 'Non' ?></td>
                      <td><?= htmlspecialchars($covoiturage['details'] ?? 'Aucun détail fourni') ?></td>
                      <td><?= htmlspecialchars($covoiturage['user_name'] ?? 'Utilisateur inconnu') ?></td>
                      <td>
                        <button class="btn edit" data-id="<?= $covoiturage['id_covoit'] ?>"><i
                            class="fas fa-edit"></i></button>
                        <button class="btn delete" data-id="<?= $covoiturage['id_covoit'] ?>"><i
                            class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <script src="search.js"></script>
            <script src="Recent.js"></script>
            <script src="Ancien.js"></script>
            <div class="pagination">
              <button class="pagination-btn prev"><i class="fas fa-chevron-left"></i></button>
              <button class="pagination-btn active">1</button>
              <button class="pagination-btn">2</button>
              <button class="pagination-btn">3</button>
              <button class="pagination-btn next"><i class="fas fa-chevron-right"></i></button>
            </div>
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


  <!-- Modal for Adding/Editing Ride -->
  <div class="modal" id="ride-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modal-title">Ajouter un Trajet</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form id="ride-form" method="POST" action="updateCovoiturage.php">
          <input type="hidden" id="id_covoit" name="id_covoit">

          <div class="form-group">
            <label for="ride-departure">Départ</label>
            <input type="text" id="ride-departure" name="departure">
            <span id="ride-departure-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-destination">Destination</label>
            <input type="text" id="ride-destination" name="destination">
            <span id="ride-destination-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-date">Date</label>
            <input type="date" id="ride-date" name="date">
            <span id="ride-date-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-time">Heure</label>
            <input type="time" id="ride-time" name="time">
            <span id="ride-time-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-seats">Places disponibles</label>
            <input type="number" id="ride-seats" name="seats">
            <span id="ride-seats-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-price">Prix par place (TND)</label>
            <input type="number" id="ride-price" name="price" step="1">
            <span id="ride-price-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="accept-parcels">Accepte les colis</label>
            <select id="accept-parcels" name="accept_parcels">
              <option value="oui">Oui</option>
              <option value="non">Non</option>
            </select>
            <span id="accept-parcels-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="full-parcels">Colis complet</label>
            <select id="full-parcels" name="full_parcels">
              <option value="oui">Oui</option>
              <option value="non">Non</option>
            </select>
            <span id="full-parcels-error" class="error-message"></span>
          </div>
          <div class="form-group">
            <label for="ride-description">Description</label>
            <textarea id="ride-description" name="description" rows="3"></textarea>
            <span id="ride-description-error" class="error-message"></span>
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
        <p>Êtes-vous sûr de vouloir supprimer ce trajet ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

  <script src="Bvalidcovoiturage.js"></script>
</body>

</html>