<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion du Blog</title>
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
            <li>
              <a href="../covoiturage/crud.php">
                <i class="fas fa-car-side"></i>
                <span>Covoiturage</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
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
          <h1>Gestion du Blog</h1>
          <p>Ajoutez, modifiez et supprimez des articles de blog</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un article...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <button class="btn primary" id="add-blog-btn">
              <i class="fas fa-plus"></i> Ajouter un Article
            </button>
          </div>
        </div>
      </header>
      
      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Articles</button>
              <button class="tab-btn" data-tab="published">Publiés</button>
              <button class="tab-btn" data-tab="draft">Brouillons</button>
              <button class="tab-btn" data-tab="archived">Archivés</button>
            </div>
            <div class="view-options">
              <button class="view-btn active" data-view="table"><i class="fas fa-list"></i></button>
              <button class="view-btn" data-view="grid"><i class="fas fa-th"></i></button>
            </div>
          </div>
          
          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="posts-table-container">
              <table class="posts-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Auteur</th>
                    <th>Date de publication</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>B001</td>
                    <td>Comment la mobilité partagée transforme nos villes</td>
                    <td>Mobilité Verte</td>
                    <td>Ahmed Ben Ali</td>
                    <td>10/06/2023</td>
                    <td><span class="status published">Publié</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>B002</td>
                    <td>5 conseils pour un covoiturage réussi</td>
                    <td>Covoiturage</td>
                    <td>Leila Mansour</td>
                    <td>05/06/2023</td>
                    <td><span class="status published">Publié</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>B003</td>
                    <td>L'avenir des bus électriques en France</td>
                    <td>Transport Public</td>
                    <td>Mohamed Khelifi</td>
                    <td>01/06/2023</td>
                    <td><span class="status published">Publié</span></td>
                    <td class="actions">
                      <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>B004</td>
                    <td>Les nouvelles technologies de transport en 2023</td>
                    <td>Nouvelles Technologies</td>
                    <td>Nadia Mansouri</td>
                    <td>-</td>
                    <td><span class="status draft">Brouillon</span></td>
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
            <div class="posts-grid">
              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-1.jpg" alt="Blog Post">
                </div>
                <div class="post-header">
                  <h3>Comment la mobilité partagée transforme nos villes</h3>
                  <span class="status published">Publié</span>
                </div>
                <div class="post-details">
                  <div class="detail">
                    <span class="label">Catégorie:</span>
                    <span>Mobilité Verte</span>
                  </div>
                  <div class="detail">
                    <span class="label">Auteur:</span>
                    <span>Ahmed Ben Ali</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date:</span>
                    <span>10/06/2023</span>
                  </div>
                </div>
                <div class="post-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
              
              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-2.jpg" alt="Blog Post">
                </div>
                <div class="post-header">
                  <h3>5 conseils pour un covoiturage réussi</h3>
                  <span class="status published">Publié</span>
                </div>
                <div class="post-details">
                  <div class="detail">
                    <span class="label">Catégorie:</span>
                    <span>Covoiturage</span>
                  </div>
                  <div class="detail">
                    <span class="label">Auteur:</span>
                    <span>Leila Mansour</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date:</span>
                    <span>05/06/2023</span>
                  </div>
                </div>
                <div class="post-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
              
              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-3.jpg" alt="Blog Post">
                </div>
                <div class="post-header">
                  <h3>L'avenir des bus électriques en France</h3>
                  <span class="status published">Publié</span>
                </div>
                <div class="post-details">
                  <div class="detail">
                    <span class="label">Catégorie:</span>
                    <span>Transport Public</span>
                  </div>
                  <div class="detail">
                    <span class="label">Auteur:</span>
                    <span>Mohamed Khelifi</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date:</span>
                    <span>01/06/2023</span>
                  </div>
                </div>
                <div class="post-actions">
                  <button class="action-btn view" title="Voir"><i class="fas fa-eye"></i></button>
                  <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                  <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>
              </div>
              
              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-1.jpg" alt="Blog Post">
                </div>
                <div class="post-header">
                  <h3>Les nouvelles technologies de transport en 2023</h3>
                  <span class="status draft">Brouillon</span>
                </div>
                <div class="post-details">
                  <div class="detail">
                    <span class="label">Catégorie:</span>
                    <span>Nouvelles Technologies</span>
                  </div>
                  <div class="detail">
                    <span class="label">Auteur:</span>
                    <span>Nadia Mansouri</span>
                  </div>
                  <div class="detail">
                    <span class="label">Date:</span>
                    <span>-</span>
                  </div>
                </div>
                <div class="post-actions">
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
  
  <!-- Modal for Adding/Editing Post -->
  <div class="modal" id="post-modal">
    <div class="modal-content large">
      <div class="modal-header">
        <h2 id="modal-title">Ajouter un Article</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form id="post-form">
          <div class="form-group">
            <label for="post-title">Titre</label>
            <input type="text" id="post-title" name="title" required>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="post-category">Catégorie</label>
              <select id="post-category" name="category" required>
                <option value="">Sélectionner une catégorie</option>
                <option value="mobilite-verte">Mobilité Verte</option>
                <option value="covoiturage">Covoiturage</option>
                <option value="transport-public">Transport Public</option>
                <option value="conseils">Conseils</option>
                <option value="nouvelles-technologies">Nouvelles Technologies</option>
              </select>
            </div>
            <div class="form-group">
              <label for="post-author">Auteur</label>
              <select id="post-author" name="author" required>
                <option value="">Sélectionner un auteur</option>
                <option value="1">Ahmed Ben Ali</option>
                <option value="2">Leila Mansour</option>
                <option value="3">Mohamed Khelifi</option>
                <option value="4">Nadia Mansouri</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="post-status">Statut</label>
              <select id="post-status" name="status" required>
                <option value="">Sélectionner un statut</option>
                <option value="published">Publié</option>
                <option value="draft">Brouillon</option>
                <option value="archived">Archivé</option>
              </select>
            </div>
            <div class="form-group">
              <label for="post-image">Image de couverture</label>
              <input type="file" id="post-image" name="image">
            </div>
          </div>
          <div class="form-group">
            <label for="post-excerpt">Extrait</label>
            <textarea id="post-excerpt" name="excerpt" rows="2" required></textarea>
          </div>
          <div class="form-group">
            <label for="post-content">Contenu</label>
            <textarea id="post-content" name="content" rows="10" required></textarea>
          </div>
          <div class="form-group">
            <label for="post-tags">Tags (séparés par des virgules)</label>
            <input type="text" id="post-tags" name="tags">
          </div>
          <div class="form-actions">
            <button type="button" class="btn secondary cancel-btn">Annuler</button>
            <button type="submit" class="btn primary" id="save-post-btn">Enregistrer</button>
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
        <p>Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.</p>
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
        
        // Filter posts based on tab (for a real application, this would use AJAX to fetch filtered data)
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
    const postModal = document.getElementById('post-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
    
    // Open Add Post Modal
    document.getElementById('add-blog-btn').addEventListener('click', function() {
      document.getElementById('modal-title').textContent = 'Ajouter un Article';
      document.getElementById('post-form').reset();
      postModal.classList.add('active');
    });
    
    // Open Edit Post Modal
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('modal-title').textContent = 'Modifier un Article';
        // Here you would populate the form with the post data
        postModal.classList.add('active');
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
        postModal.classList.remove('active');
        deleteModal.classList.remove('active');
      });
    });
    
    // Form Submit Handler (would normally use AJAX)
    document.getElementById('post-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Article enregistré avec succès!');
      postModal.classList.remove('active');
    });
    
    // Delete Confirmation Handler
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
      // Here you would send a delete request to the server
      alert('Article supprimé avec succès!');
      deleteModal.classList.remove('active');
    });
  </script>
</body>
</html>
