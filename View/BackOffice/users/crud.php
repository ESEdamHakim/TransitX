<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Utilisateurs</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/users.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <li class="active">
              <a href="index.php">
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
          <h1>Gestion des Utilisateurs</h1>
          <p>Ajoutez, modifiez et supprimez des utilisateurs</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un utilisateur...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <button class="btn primary" id="add-user-btn">
              <i class="fas fa-plus"></i> Ajouter un Utilisateur
            </button>
          </div>
        </div>
      </header>
      
      <div class="dashboard-content">
        <div class="users-container">
          <div class="users-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Utilisateurs</button>
              <button class="tab-btn" data-tab="admin">Administrateurs</button>
              <button class="tab-btn" data-tab="client">Clients</button>
              <button class="tab-btn" data-tab="driver">Chauffeurs</button>
            </div>
            <div class="view-options">
              <button class="view-btn active" data-view="table"><i class="fas fa-list"></i></button>
              <button class="view-btn" data-view="grid"><i class="fas fa-th"></i></button>
            </div>
          </div>
          
          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="users-table-container">
              <table class="users-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>U001</td>
                    <td>Ahmed Ben Ali</td>
                    <td>ahmed@example.com</td>
                    <td>+216 12 345 678</td>
                    <td>Client</td>
                    <td><span class="status active">Actif</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>U002</td>
                    <td>Leila Mansour</td>
                    <td>leila@example.com</td>
                    <td>+216 23 456 789</td>
                    <td>Administrateur</td>
                    <td><span class="status active">Actif</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>U003</td>
                    <td>Mohamed Khelifi</td>
                    <td>mohamed@example.com</td>
                    <td>+216 34 567 890</td>
                    <td>Chauffeur</td>
                    <td><span class="status inactive">Inactif</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>U004</td>
                    <td>Nadia Mansouri</td>
                    <td>nadia@example.com</td>
                    <td>+216 45 678 901</td>
                    <td>Client</td>
                    <td><span class="status active">Actif</span></td>
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
            <div class="users-grid">
              <!-- User cards will go here -->
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
  
  <!-- Modal for Adding/Editing User -->
  <div class="modal" id="user-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modal-title">Ajouter un Utilisateur</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form id="user-form">
          <div class="form-row">
            <div class="form-group">
              <label for="user-firstname">Prénom</label>
              <input type="text" id="user-firstname" name="firstname" required>
            </div>
            <div class="form-group">
              <label for="user-lastname">Nom</label>
              <input type="text" id="user-lastname" name="lastname" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="user-email">Email</label>
              <input type="email" id="user-email" name="email" required>
            </div>
            <div class="form-group">
              <label for="user-phone">Téléphone</label>
              <input type="tel" id="user-phone" name="phone" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="user-role">Rôle</label>
              <select id="user-role" name="role" required>
                <option value="">Sélectionner un rôle</option>
                <option value="admin">Administrateur</option>
                <option value="client">Client</option>
                <option value="driver">Chauffeur</option>
              </select>
            </div>
            <div class="form-group">
              <label for="user-status">Statut</label>
              <select id="user-status" name="status" required>
                <option value="">Sélectionner un statut</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="user-address">Adresse</label>
            <textarea id="user-address" name="address" rows="3"></textarea>
          </div>
          <div class="form-actions">
            <button type="button" class="btn secondary cancel-btn">Annuler</button>
            <button type="submit" class="btn primary" id="save-user-btn">Enregistrer</button>
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
        <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
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
        
        // Filter users based on tab
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
    const userModal = document.getElementById('user-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
    
    // Open Add User Modal
    document.getElementById('add-user-btn').addEventListener('click', function() {
      document.getElementById('modal-title').textContent = 'Ajouter un Utilisateur';
      document.getElementById('user-form').reset();
      userModal.classList.add('active');
    });
    
    // Open Edit User Modal
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Modifier un Utilisateur';
        // Here you would populate the form with the user data
        userModal.classList.add('active');
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
        userModal.classList.remove('active');
        deleteModal.classList.remove('active');
      });
    });
    
    // Form Submit Handler
    document.getElementById('user-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Utilisateur enregistré avec succès!');
      userModal.classList.remove('active');
    });
    
    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
      // Here you would send a delete request to the server
      alert('Utilisateur supprimé avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
</body>
</html>
