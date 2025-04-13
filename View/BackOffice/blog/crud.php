

<?php
try {
  require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
} catch (Exception $e) {
    echo 'Erreur lors de l\'inclusion du fichier : ' . $e->getMessage();
}


$articc = new ArticleC();  
$list = $articc->listoffre();
?>
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
         
      </header>
      
      <!-- Formulaire -->
    <section class="form-section">
      <h2>Ajouter un Article</h2>
      <form action="addarticle.php" method="POST" class="form-container">
        <!-- Article -->
        <fieldset>
          <legend>Ajouter un Article</legend>
          <div class="form-group">
            <label for="id_article">ID Article :</label>
            <input type="number" id="id_article" name="id_article" class="form-input" required />
          </div>
          <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" class="form-input" required />
          </div>
          <div class="form-group">
            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" class="form-textarea" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label for="date_publication">Date de Publication :</label>
            <input type="date" id="date_publication" name="date_publication" class="form-input" required />
          </div>
        </fieldset>

        <button type="submit" class="btn">Ajouter</button>
      </form>
      <table border="1">
            <tr>
                <th>id_article</th>
                <th>titre</th>
                <th>contenu</th>
                <th>date_publication</th>
                <th>Actions</th>  <!-- Ajouter une colonne pour les actions -->
            </tr>
            <?php
            foreach ($list as $offer) {
            ?>
                <tr>
                    <td><?= htmlspecialchars($offer['id_article']); ?></td>
                    <td><?= htmlspecialchars($offer['titre']); ?></td>
                    <td><?= htmlspecialchars($offer['contenu']); ?></td>
                    <td><?= htmlspecialchars($offer['date_publication']); ?></td>
                    <td>
                        <a href="updatearticle.php?id=<?= htmlspecialchars($offer['id_article']); ?>">Update</a>
                        <a href="deletearticle.php?id_article=<?= htmlspecialchars($offer['id_article']); ?>" onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
                        </td>
                </tr>
            <?php
            }
            ?>
    </section>
          
       
</body>
</html>
