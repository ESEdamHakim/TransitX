<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Bus</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
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
            <li>
              <a href="../users/crud.php">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
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
          <h1>Gestion des Bus</h1>
          <p>Ajoutez, modifiez et supprimez des bus</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un bus...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <button class="btn primary" id="add-bus-btn">
              <i class="fas fa-plus"></i> Ajouter un Bus
            </button>
          </div>
        </div>
      </header>
      
      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Bus</button>
              <button class="tab-btn" data-tab="active">Actifs</button>
              <button class="tab-btn" data-tab="maintenance">En Maintenance</button>
              <button class="tab-btn" data-tab="inactive">Inactifs</button>
            </div>
            <div class="view-options">
              <button class="view-btn active" data-view="table"><i class="fas fa-list"></i></button>
              <button class="view-btn" data-view="grid"><i class="fas fa-th"></i></button>
            </div>
          </div>
          
          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="buses-table-container">
              <table class="buses-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Modèle</th>
                    <th>Immatriculation</th>
                    <th>Capacité</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                   <!-- The table is now empty. You can dynamically populate it later using PHP or JavaScript -->
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
            <div class="buses-grid">
              <!-- The grid is now empty. You can dynamically populate it later using PHP or JavaScript --> 
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
  
  <!-- Modal for Adding/Editing Bus -->
  <div class="modal" id="bus-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modal-title">Ajouter un Bus</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form id="bus-form">
          <div class="form-row">
            <div class="form-group">
              <label for="bus-model">Modèle</label>
              <input type="text" id="bus-model" name="model" required>
            </div>
            <div class="form-group">
              <label for="bus-registration">Immatriculation</label>
              <input type="text" id="bus-registration" name="registration" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="bus-capacity">Capacité</label>
              <input type="number" id="bus-capacity" name="capacity" min="1" required>
            </div>
            <div class="form-group">
              <label for="bus-type">Type</label>
              <select id="bus-type" name="type" required>
                <option value="">Sélectionner un type</option>
                <option value="Électrique">Électrique</option>
                <option value="Hybride">Hybride</option>
                <option value="Diesel">Diesel</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="bus-status">Statut</label>
              <select id="bus-status" name="status" required>
                <option value="">Sélectionner un statut</option>
                <option value="active">Actif</option>
                <option value="maintenance">En maintenance</option>
                <option value="inactive">Inactif</option>
              </select>
            </div>
            <div class="form-group">
              <label for="bus-image">Image</label>
              <input type="file" id="bus-image" name="image">
            </div>
          </div>
          <div class="form-group">
            <label for="bus-description">Description</label>
            <textarea id="bus-description" name="description" rows="3"></textarea>
          </div>
          <div class="form-actions">
            <button type="button" class="btn secondary cancel-btn">Annuler</button>
            <button type="submit" class="btn primary" id="save-bus-btn">Enregistrer</button>
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
        <p>Êtes-vous sûr de vouloir supprimer ce bus ? Cette action est irréversible.</p>
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
        
        // Filter buses based on tab (for a real application, this would use AJAX to fetch filtered data)
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
    const busModal = document.getElementById('bus-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
    
    // Open Add Bus Modal
    document.getElementById('add-bus-btn').addEventListener('click', function() {
      document.getElementById('modal-title').textContent = 'Ajouter un Bus';
      document.getElementById('bus-form').reset();
      busModal.classList.add('active');
    });
    
    // Open Edit Bus Modal
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Modifier un Bus';
        // Here you would populate the form with the bus data
        busModal.classList.add('active');
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
        busModal.classList.remove('active');
        deleteModal.classList.remove('active');
      });
    });
    
    // Form Submit Handler (would normally use AJAX)
    document.getElementById('bus-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Bus enregistré avec succès!');
      busModal.classList.remove('active');
    });
    
    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
      // Here you would send a delete request to the server
      alert('Bus supprimé avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
</body>
</html>
