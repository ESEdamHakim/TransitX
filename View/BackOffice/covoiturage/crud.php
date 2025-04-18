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
      color: #1f4f65; /* Blue color for the title */
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
          <div class="actions">
            <button class="btn primary" id="add-covoiturage-btn">
              <i class="fas fa-plus"></i> Ajouter un Trajet
            </button>
          </div>
        </div>
      </header>
      
      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Trajets</button>
              <button class="tab-btn" data-tab="active">Actifs</button>
              <button class="tab-btn" data-tab="pending">En attente</button>
              <button class="tab-btn" data-tab="completed">Terminés</button>
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
                    <th>Conducteur</th>
                    <th>Départ</th>
                    <th>Destination</th>
                    <th>Date & Heure</th>
                    <th>Places</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>C001</td>
                    <td>Ahmed Ben Ali</td>
                    <td>Tunis</td>
                    <td>Sousse</td>
                    <td>15/06/2023 18:00</td>
                    <td>3/4</td>
                    <td>20 TND</td>
                    <td><span class="status active">Actif</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>C002</td>
                    <td>Leila Mansour</td>
                    <td>Sousse</td>
                    <td>Sfax</td>
                    <td>16/06/2023 10:30</td>
                    <td>2/3</td>
                    <td>15 TND</td>
                    <td><span class="status active">Actif</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>C003</td>
                    <td>Mohamed Khelifi</td>
                    <td>Hammamet</td>
                    <td>Monastir</td>
                    <td>17/06/2023 14:00</td>
                    <td>1/4</td>
                    <td>12 TND</td>
                    <td><span class="status pending">En attente</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>C004</td>
                    <td>Nadia Mansouri</td>
                    <td>Tunis</td>
                    <td>Bizerte</td>
                    <td>14/06/2023 09:00</td>
                    <td>4/4</td>
                    <td>10 TND</td>
                    <td><span class="status completed">Terminé</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
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
              <div class="ride-card">
                <div class="ride-header">
                  <h3>Tunis → Sousse</h3>
                  <span class="status active">Actif</span>
                </div>
                <div class="ride-details">
                  <div class="detail">
                    <span class="label">Conducteur:</span>
                    <span>Ahmed Ben Ali</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date & Heure:</span>
                    <span>15/06/2023 18:00</span>
                  </div>
                  <div class="detail">
                    <span class="label">Places:</span>
                    <span>3/4</span>
                  </div>
                  <div class="detail">
                    <span class="label">Prix:</span>
                    <span>20 TND</span>
                  </div>
                </div>
                <div class="ride-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
              
              <div class="ride-card">
                <div class="ride-header">
                  <h3>Sousse → Sfax</h3>
                  <span class="status active">Actif</span>
                </div>
                <div class="ride-details">
                  <div class="detail">
                    <span class="label">Conducteur:</span>
                    <span>Leila Mansour</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date & Heure:</span>
                    <span>16/06/2023 10:30</span>
                  </div>
                  <div class="detail">
                    <span class="label">Places:</span>
                    <span>2/3</span>
                  </div>
                  <div class="detail">
                    <span class="label">Prix:</span>
                    <span>15 TND</span>
                  </div>
                </div>
                <div class="ride-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
              
              <div class="ride-card">
                <div class="ride-header">
                  <h3>Hammamet → Monastir</h3>
                  <span class="status pending">En attente</span>
                </div>
                <div class="ride-details">
                  <div class="detail">
                    <span class="label">Conducteur:</span>
                    <span>Mohamed Khelifi</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date & Heure:</span>
                    <span>17/06/2023 14:00</span>
                  </div>
                  <div class="detail">
                    <span class="label">Places:</span>
                    <span>1/4</span>
                  </div>
                  <div class="detail">
                    <span class="label">Prix:</span>
                    <span>12 TND</span>
                  </div>
                </div>
                <div class="ride-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
              
              <div class="ride-card">
                <div class="ride-header">
                  <h3>Tunis → Bizerte</h3>
                  <span class="status completed">Terminé</span>
                </div>
                <div class="ride-details">
                  <div class="detail">
                    <span class="label">Conducteur:</span>
                    <span>Nadia Mansouri</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date & Heure:</span>
                    <span>14/06/2023 09:00</span>
                  </div>
                  <div class="detail">
                    <span class="label">Places:</span>
                    <span>4/4</span>
                  </div>
                  <div class="detail">
                    <span class="label">Prix:</span>
                    <span>10 TND</span>
                  </div>
                </div>
                <div class="ride-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
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
        <form id="ride-form">
          <div class="form-row">
            <div class="form-group">
              <label for="ride-departure">Départ</label>
              <input type="text" id="ride-departure" name="departure" required>
            </div>
            <div class="form-group">
              <label for="ride-destination">Destination</label>
              <input type="text" id="ride-destination" name="destination" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="ride-date">Date</label>
              <input type="date" id="ride-date" name="date" required>
            </div>
            <div class="form-group">
              <label for="ride-time">Heure</label>
              <input type="time" id="ride-time" name="time" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="ride-seats">Places disponibles</label>
              <input type="number" id="ride-seats" name="seats" min="1" max="8" required>
            </div>
            <div class="form-group">
              <label for="ride-price">Prix par place (TND)</label>
              <input type="number" id="ride-price" name="price" min="1" step="0.5" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="ride-driver">Conducteur</label>
              <select id="ride-driver" name="driver" required>
                <option value="">Sélectionner un conducteur</option>
                <option value="1">Ahmed Ben Ali</option>
                <option value="2">Leila Mansour</option>
                <option value="3">Mohamed Khelifi</option>
                <option value="4">Nadia Mansouri</option>
              </select>
            </div>
            <div class="form-group">
              <label for="ride-status">Statut</label>
              <select id="ride-status" name="status" required>
                <option value="">Sélectionner un statut</option>
                <option value="active">Actif</option>
                <option value="pending">En attente</option>
                <option value="completed">Terminé</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="ride-description">Description</label>
            <textarea id="ride-description" name="description" rows="3"></textarea>
          </div>
          <div class="form-actions">
            <button type="button" class="btn secondary cancel-btn">Annuler</button>
            <button type="submit" class="btn primary" id="save-ride-btn">Enregistrer</button>
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

  <script>
    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });

    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Filter rides based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });

    // View Switching
    const viewButtons = document.querySelectorAll('.view-btn');
    const viewContainers = document.querySelectorAll('.view-container');
    viewButtons.forEach(button => {
      button.addEventListener('click', function() {
        viewButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        const viewType = this.getAttribute('data-view');
        viewContainers.forEach(container => {
          container.classList.remove('active');
          if (container.classList.contains(`${viewType}-view`)) {
            container.classList.add('active');
          }
        });
      });
    });

    // Modal Functions
    const rideModal = document.getElementById('ride-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
    
    // Open Add Ride Modal
    document.getElementById('add-covoiturage-btn').addEventListener('click', function() {
      document.getElementById('modal-title').textContent = 'Ajouter un Trajet';
      document.getElementById('ride-form').reset();
      rideModal.classList.add('active');
    });
    
    // Open Edit Ride Modal
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Modifier un Trajet';
        // Here you would populate the form with the ride data
        rideModal.classList.add('active');
      });
    });
    
    // Open Delete Confirmation Modal
    const deleteButtons = document.querySelectorAll('.delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        deleteModal.classList.add('active');
      });
    });
    
    // Close Modals
    closeButtons.forEach(button => {
      button.addEventListener('click', function() {
        rideModal.classList.remove('active');
        deleteModal.classList.remove('active');
      });
    });
    
    // Form Submit Handler (would normally use AJAX)
    document.getElementById('ride-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Trajet enregistré avec succès!');
      rideModal.classList.remove('active');
    });
    
    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
      // Here you would send a delete request to the server
      alert('Trajet supprimé avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
</body>
</html>
