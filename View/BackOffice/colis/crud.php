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
  <style>
    .status {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .status.pending {
      background-color: #fff3cd;
      color: #856404;
    }
    
    .status.in-transit {
      background-color: #cce5ff;
      color: #004085;
    }
    
    .status.delivered {
      background-color: #d4edda;
      color: #155724;
    }
    
    .status.cancelled {
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
      border-left: 4px solid #dc3545;
    }
    
    .colis-filters {
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
    
    .filter-item select, .filter-item input {
      padding: 0.5rem;
      border: 1px solid #ced4da;
      border-radius: 4px;
    }
    
    .filter-actions {
      margin-left: auto;
    }
    
    .parcels-table th, .parcels-table td {
      padding: 0.75rem 1rem;
    }
    
    .parcels-table th {
      background-color: #f8f9fa;
      font-weight: 600;
    }
    
    .parcels-table tr:hover {
      background-color: #f8f9fa;
    }
    
    .action-btn {
      width: 32px;
      height: 32px;
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
            <button class="btn primary" id="add-colis-btn">
              <i class="fas fa-plus"></i> Ajouter un Colis
            </button>
          </div>
        </div>
      </header>
      
      <div class="dashboard-content">
        <!-- Stats Overview -->
        <div class="dashboard-stats">
          <div class="stat-box primary">
            <div class="stat-title">Total des colis</div>
            <div class="stat-value">128</div>
            <div class="stat-icon"><i class="fas fa-box"></i></div>
          </div>
          <div class="stat-box success">
            <div class="stat-title">Colis livrés</div>
            <div class="stat-value">76</div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
          </div>
          <div class="stat-box warning">
            <div class="stat-title">En transit</div>
            <div class="stat-value">42</div>
            <div class="stat-icon"><i class="fas fa-truck"></i></div>
          </div>
          <div class="stat-box danger">
            <div class="stat-title">En attente</div>
            <div class="stat-value">10</div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
          </div>
        </div>
        
        <!-- Filters -->
        <div class="colis-filters">
          <div class="filter-item">
            <label for="status-filter">Statut:</label>
            <select id="status-filter">
              <option value="all">Tous</option>
              <option value="pending">En attente</option>
              <option value="in-transit">En transit</option>
              <option value="delivered">Livrés</option>
              <option value="cancelled">Annulés</option>
            </select>
          </div>
          <div class="filter-item">
            <label for="date-filter">Date:</label>
            <input type="date" id="date-filter">
          </div>
          <div class="filter-item">
            <label for="city-filter">Ville:</label>
            <select id="city-filter">
              <option value="all">Toutes</option>
              <option value="tunis">Tunis</option>
              <option value="sousse">Sousse</option>
              <option value="sfax">Sfax</option>
              <option value="monastir">Monastir</option>
            </select>
          </div>
          <div class="filter-actions">
            <button class="btn primary">Appliquer</button>
            <button class="btn secondary">Réinitialiser</button>
          </div>
        </div>
        
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Colis</button>
              <button class="tab-btn" data-tab="pending">En attente</button>
              <button class="tab-btn" data-tab="transit">En transit</button>
              <button class="tab-btn" data-tab="delivered">Livrés</button>
            </div>
          </div>
          
          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="colis-table-container">
              <table class="parcels-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Expéditeur</th>
                    <th>Destinataire</th>
                    <th>Adresse Livraison</th>
                    <th>Date d'envoi</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>C001</td>
                    <td>Ahmed Ben Ali</td>
                    <td>Sami Trabelsi</td>
                    <td>123 Rue Habib Bourguiba, Tunis</td>
                    <td>20/04/2025</td>
                    <td><span class="status in-transit">En transit</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>C002</td>
                    <td>Leila Mansour</td>
                    <td>Karim Belhaj</td>
                    <td>45 Avenue Mohamed V, Sousse</td>
                    <td>19/04/2025</td>
                    <td><span class="status delivered">Livré</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>C003</td>
                    <td>Mohamed Khelifi</td>
                    <td>Nadia Mansouri</td>
                    <td>78 Rue Ibn Khaldoun, Sfax</td>
                    <td>18/04/2025</td>
                    <td><span class="status pending">En attente</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>C004</td>
                    <td>Sarah Meddeb</td>
                    <td>Yassine Jouini</td>
                    <td>12 Avenue Habib Thameur, Monastir</td>
                    <td>17/04/2025</td>
                    <td><span class="status delivered">Livré</span></td>
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
        </div>
      </div>
    </main>
  </div>
  
  <!-- Modal for Adding/Editing Colis -->
  <div class="modal" id="colis-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modal-title">Ajouter un Colis</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form id="colis-form">
          <div class="form-group">
            <label for="colis-sender">Expéditeur</label>
            <input type="text" id="colis-sender" name="sender" required>
          </div>
          <div class="form-group">
            <label for="colis-recipient">Destinataire</label>
            <input type="text" id="colis-recipient" name="recipient" required>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="colis-pickup">Adresse de ramassage</label>
              <input type="text" id="colis-pickup" name="pickup" required>
            </div>
            <div class="form-group">
              <label for="colis-delivery">Adresse de livraison</label>
              <input type="text" id="colis-delivery" name="delivery" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="colis-weight">Poids (kg)</label>
              <input type="number" id="colis-weight" name="weight" min="0.1" step="0.1" required>
            </div>
            <div class="form-group">
              <label for="colis-date">Date d'envoi</label>
              <input type="date" id="colis-date" name="date" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="colis-status">Statut</label>
              <select id="colis-status" name="status" required>
                <option value="">Sélectionner un statut</option>
                <option value="pending">En attente</option>
                <option value="in-transit">En transit</option>
                <option value="delivered">Livré</option>
                <option value="cancelled">Annulé</option>
              </select>
            </div>
            <div class="form-group">
              <label for="colis-price">Prix (TND)</label>
              <input type="number" id="colis-price" name="price" min="1" step="0.1" required>
            </div>
          </div>
          <div class="form-group">
            <label for="colis-description">Description</label>
            <textarea id="colis-description" name="description" rows="3"></textarea>
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
        <p>Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.</p>
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
        
        // Filter colis based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });

    // Modal Functions
    const colisModal = document.getElementById('colis-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
    
    // Open Add Colis Modal
    document.getElementById('add-colis-btn').addEventListener('click', function() {
      document.getElementById('modal-title').textContent = 'Ajouter un Colis';
      document.getElementById('colis-form').reset();
      colisModal.classList.add('active');
    });
    
    // Open Edit Colis Modal
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Modifier un Colis';
        // Here you would populate the form with the colis data
        colisModal.classList.add('active');
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
        colisModal.classList.remove('active');
        deleteModal.classList.remove('active');
      });
    });
    
    // Form Submit Handler (would normally use AJAX)
    document.getElementById('colis-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Colis enregistré avec succès!');
      colisModal.classList.remove('active');
    });
    
    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
      // Here you would send a delete request to the server
      alert('Colis supprimé avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
</body>
</html>
