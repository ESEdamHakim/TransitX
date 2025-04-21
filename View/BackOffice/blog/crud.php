<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
  require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
} catch (Exception $e) {
  echo 'Erreur lors de l\'inclusion du fichier : ' . $e->getMessage();
}

$articc = new ArticleC();  
$list = $articc->listarticle();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TransitX - Gestion du Blog</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .header-left h1 {
      color: #1f4f65;
    }
    .buses-table-container {
      margin-top: 30px;
      overflow-x: auto;
    }
    .buses-table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      background-color: #fff;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    .buses-table thead {
      background-color: #1f4f65;
      color: #fff;
    }
    .buses-table th, .buses-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }
    .buses-table tr:hover {
      background-color: #f9f9f9;
    }
    .actions {
      display: flex;
      gap: 8px;
    }
    .action-btn {
      border: none;
      background: none;
      cursor: pointer;
      color: #333;
      font-size: 16px;
      transition: transform 0.2s;
    }
    .action-btn:hover {
      transform: scale(1.1);
    }
    .action-btn.edit {
      color: #007bff;
    }
    .action-btn.delete {
      color: #dc3545;
    }
    .error-message {
      color: red;
      font-size: 0.9em;
      margin-top: 4px;
      display: block;
      text-decoration: underline;
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
        <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
      </div>

      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li><a href="../users/crud.php"><i class="fas fa-users"></i><span>Utilisateurs</span></a></li>
            <li><a href="../bus/crud.php"><i class="fas fa-bus"></i><span>Bus</span></a></li>
            <li><a href="../colis/crud.php"><i class="fas fa-box"></i><span>Colis</span></a></li>
            <li><a href="../reclamations/crud.php"><i class="fas fa-exclamation-circle"></i><span>Réclamations</span></a></li>
            <li><a href="../covoiturage/crud.php"><i class="fas fa-car-side"></i><span>Covoiturage</span></a></li>
            <li class="active"><a href="crud.php"><i class="fas fa-blog"></i><span>Blog</span></a></li>
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
        </div>
      </header>

      <!-- Formulaire d'ajout -->
      <section class="form-section">
        <h2>Ajouter un Article</h2>
        <form id="articleForm" action="addarticle.php" method="POST" enctype="multipart/form-data" class="form-container" onsubmit="return validateForm();">
          <fieldset>
            <legend>Ajouter un Article</legend>

            <div class="form-group">
              <label for="titre">Titre :</label>
              <input type="text" id="titre" name="titre" class="form-input" />
              <span class="error-message" id="titre-error"></span>
            </div>

            <div class="form-group">
              <label for="contenu">Contenu :</label>
              <textarea id="contenu" name="contenu" class="form-textarea" rows="4"></textarea>
              <span class="error-message" id="contenu-error"></span>
            </div>

            <div class="form-group">
              <label for="date_publication">Date de Publication :</label>
              <input type="date" id="date_publication" name="date_publication" class="form-input" />
              <span class="error-message" id="date-error"></span>
            </div>

            <div class="form-group">
              <label for="photo">Photo :</label>
              <input type="file" id="photo" name="photo" accept="image/*" class="form-input" />
              <span class="error-message" id="photo-error"></span>
            </div>

          </fieldset>
          <button type="submit" class="btn">Ajouter</button>
        </form>
        
        <!-- Formulaire de recherche d'articles -->
        <form action="searchArticles.php" method="GET">
  <label for="article">Choisir un article :</label>
  <select name="article" id="article">
    <?php
    // Connexion à la base de données pour récupérer tous les articles
    try {
      $pdo = new PDO('mysql:host=localhost;dbname=transitx', 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = $pdo->query("SELECT * FROM article");
      $articles = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }

    // Affichage des articles dans un menu déroulant
    foreach ($articles as $article) {
        echo "<option value='" . $article['id_article'] . "'>" . $article['titre'] . "</option>";
    }
    ?>
  </select>
  <button type="submit">Afficher les commentaires</button>
</form>



      <!-- Tableau stylisé -->
      <div class="buses-table-container">
        <table class="buses-table">
          <thead>
            <tr>
              <th>ID Article</th>
              <th>Titre</th>
              <th>Contenu</th>
              <th>Date Publication</th>
              <th>Photo</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($list as $offer) { ?>
              <tr>
                <td><?= htmlspecialchars($offer['id_article']); ?></td>
                <td><?= htmlspecialchars($offer['titre']); ?></td>
                <td><?= htmlspecialchars($offer['contenu']); ?></td>
                <td><?= htmlspecialchars($offer['date_publication']); ?></td>
                <td>
                  <?php if (!empty($offer['photo'])): ?>
                    <img src="../../../uploads/<?= htmlspecialchars($offer['photo']); ?>" alt="Photo Article" style="width: 80px;">
                  <?php else: ?>
                    Aucune image
                  <?php endif; ?>
                </td>
                <td class="actions">
                  <a href="updatearticle.php?id=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn edit" title="Modifier">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="deletearticle.php?id_article=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn delete" title="Supprimer">
                  <i class="fas fa-trash-alt"></i>
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script>
    // Fonction de validation pour le formulaire d'ajout
    function validateForm() {
      let valid = true;

      // Validation du titre
      const titre = document.getElementById("titre").value;
      if (titre === "") {
        document.getElementById("titre-error").textContent = "Le titre est requis.";
        valid = false;
      }

      // Validation du contenu
      const contenu = document.getElementById("contenu").value;
      if (contenu === "") {
        document.getElementById("contenu-error").textContent = "Le contenu est requis.";
        valid = false;
      }

      // Validation de la date de publication
      const date_publication = document.getElementById("date_publication").value;
      if (date_publication === "") {
        document.getElementById("date-error").textContent = "La date de publication est requise.";
        valid = false;
      }

      // Validation de la photo
      const photo = document.getElementById("photo").value;
      if (photo === "") {
        document.getElementById("photo-error").textContent = "La photo est requise.";
        valid = false;
      }

      return valid;
    }
  </script>
</body>
</html>
