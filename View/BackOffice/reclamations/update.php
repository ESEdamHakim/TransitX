<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Modifier un Colis</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <img src="../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
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
              <a href="../users/index.php">
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
        <a href="../../index.php" class="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </a>
      </div>
    </aside>
    
    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Modifier un Colis</h1>
          <p>Mettre à jour les informations du colis</p>
        </div>
        <div class="header-right">
          <div class="actions">
            <a href="crud.php" class="btn secondary">
              <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
          </div>
        </div>
      </header>
      
      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <h2>Colis #C001</h2>
          </div>
          <div class="view-container active">
            <form id="edit-colis-form">
              <div class="form-row">
                <div class="form-group">
                  <label for="sender-name">Nom de l'expéditeur</label>
                  <input type="text" id="sender-name" name="sender-name" value="Ahmed Ben Ali" required>
                </div>
                <div class="form-group">
                  <label for="sender-email">Email de l'expéditeur</label>
                  <input type="email" id="sender-email" name="sender-email" value="ahmed.benali@example.com" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="sender-phone">Téléphone de l'expéditeur</label>
                  <input type="tel" id="sender-phone" name="sender-phone" value="+216 55 123 456" required>
                </div>
                <div class="form-group">
                  <label for="pickup-address">Adresse de ramassage</label>
                  <input type="text" id="pickup-address" name="pickup-address" value="45 Rue Habib Bourguiba, Tunis" required>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="recipient-name">Nom du destinataire</label>
                  <input type="text" id="recipient-name" name="recipient-name" value="Sami Trabelsi" required>
                </div>
                <div class="form-group">
                  <label for="recipient-email">Email du destinataire</label>
                  <input type="email" id="recipient-email" name="recipient-email" value="sami.trabelsi@example.com" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="recipient-phone">Téléphone du destinataire</label>
                  <input type="tel" id="recipient-phone" name="recipient-phone" value="+216 55 789 012" required>
                </div>
                <div class="form-group">
                  <label for="delivery-address">Adresse de livraison</label>
                  <input type="text" id="delivery-address" name="delivery-address" value="123 Rue Habib Bourguiba, Sousse" required>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="send-date">Date d'envoi</label>
                  <input type="date" id="send-date" name="send-date" value="2023-04-20" required>
                </div>
                <div class="form-group">
                  <label for="delivery-date">Date de livraison estimée</label>
                  <input type="date" id="delivery-date" name="delivery-date" value="2023-04-22" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="weight">Poids (kg)</label>
                  <input type="number" id="weight" name="weight" value="2.5" step="0.1" min="0.1" required>
                </div>
                <div class="form-group">
                  <label for="price">Prix (TND)</label>
                  <input type="number" id="price" name="price" value="15.00" step="0.01" min="1" required>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="status">Statut</label>
                  <select id="status" name="status" required>
                    <option value="pending">En attente</option>
                    <option value="in-transit" selected>En transit</option>
                    <option value="delivered">Livré</option>
                    <option value="cancelled">Annulé</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="tracking-number">Numéro de suivi</label>
                  <input type="text" id="tracking-number" name="tracking-number" value="TRX123456789" readonly>
                </div>
              </div>
              
              <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">Documents importants et matériel de bureau. Manipuler avec soin.
Le colis contient des documents confidentiels et fragiles.</textarea>
              </div>
              
              <div class="form-actions">
                <button type="button" class="btn secondary" onclick="window.location.href='crud.php'">Annuler</button>
                <button type="submit" class="btn primary">Enregistrer les modifications</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });
    
    // Form Submit Handler
    document.getElementById('edit-colis-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // Here you would send the form data to the server
      alert('Colis mis à jour avec succès!');
      window.location.href = 'crud.php';
    });
  </script>
</body>
</html>
