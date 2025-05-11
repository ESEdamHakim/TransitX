<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../Controller/ArticleC.php';

$articleC = new ArticleC();

// Traitement du tri par date
$order = 'DESC'; // valeur par défaut
if (isset($_GET['sort'])) {
  if ($_GET['sort'] === 'date_asc') {
    $order = 'ASC';
  } elseif ($_GET['sort'] === 'date_desc') {
    $order = 'DESC';
  }
}
$categoryStats = $articleC->getArticleCountByCategory();
$categories = [];
$counts = [];

foreach ($categoryStats as $stat) {
  $categories[] = $stat['categorie'];
  $counts[] = $stat['count'];
}
function getCategoryColor($categorie)
{
  switch (strtolower(trim($categorie))) {
    case 'conseils de voyage':
      return '#2ecc71';
    case 'sécurité':
      return '#f1c40f';
    case 'Économie et écologie':
      return '#e74c3c';
    default:
      return '#1f4f65';
  }
}

function getCategoryIcon($categorie)
{
  switch (strtolower($categorie)) {
    case 'économie':
      return 'fas fa-chart-line';
    case 'écologie':
      return 'fas fa-leaf';
    case 'politique':
      return 'fas fa-balance-scale';
    case 'technologie':
      return 'fas fa-microchip';
    case 'santé':
      return 'fas fa-heartbeat';
    default:
      return 'fas fa-newspaper'; // icône générique
  }
}
$categorie = $_GET['categorie'] ?? null;
$auteur = $_GET['auteur'] ?? null;

// Liste des articles triés par date, catégorie et auteur
$list = $articleC->listarticleFilteredByCategoryAndAuthor($order, $categorie, $auteur);
// Articles les plus commentés
$topArticles = $articleC->getMostCommentedArticles();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TransitX - Gestion du Blog</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    .search-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 350px;
      padding: 8px 12px;
      border-radius: 30px;
      background-color: #f1f1f1;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
    }

    .search-bar:hover {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .search-bar input {
      flex-grow: 1;
      border: none;
      outline: none;
      padding: 10px;
      font-size: 16px;
      border-radius: 25px;
      background-color: transparent;
      color: #333;
      transition: all 0.3s ease;
    }

    .search-bar input::placeholder {
      color: #888;
    }

    .search-bar input:focus {
      color: #333;
      border-color: #1f4f65;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .search-bar button {
      background-color: #1f4f65;
      border: none;
      padding: 10px 12px;
      margin-left: 10px;
      border-radius: 50%;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .search-bar button i {
      color: white;
      font-size: 18px;
    }

    .search-bar button:hover {
      background-color: #0056b3;
      transform: scale(1.1);
    }

    .search-bar button:focus {
      outline: none;
    }

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

    .buses-table tr:hover {
      background-color: transparent;
    }

    .buses-table th,
    .buses-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    form button {
      padding: 10px 25px;
      background-color: #1f4f65;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    form button:hover {
      background-color: rgb(45, 124, 160);
      transform: scale(1.05);
    }

    form button:focus {
      outline: none;
    }

    form button:active {
      background-color: rgb(120, 137, 145);
    }

    .select-article-container {
      display: flex;
      flex-direction: column;
      width: 100%;
      margin-bottom: 20px;
    }

    .select-article-container label {
      font-size: 16px;
      color: #1f4f65;
      margin-bottom: 8px;
    }

    .select-article-container select {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 12px;
      background-color: #f9f9f9;
      width: 100%;
      max-width: 400px;
    }

    .select-article-container button {
      padding: 10px 20px;
      background-color: #1f4f65;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .select-article-container button:hover {
      background-color: #0056b3;
    }

    .form-section {
      margin: 20px;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

    .statsChart {
      max-width: 800px;
      max-height: 400px;
      width: 100%;
      height: auto;
    }

    #showStatsBtn {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 30px;
      color: #007bff;
      transition: color 0.2s;
    }

    #showStatsBtn:hover {
      color: #0056b3;
    }

    .filtre-form {
      display: flex;
      flex-direction: column;
      gap: 20px;
      max-width: 100%;
      /* Passe de 480px à 100% */
      margin: 40px 0 40px 20px;
      padding: 25px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      font-size: 14px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .input-group label {
      font-weight: bold;
      color: #333;
    }

    .input-group select,
    .input-group input {
      padding: 12px;
      font-size: 14px;
      border-radius: 8px;
      border: 1px solid #ddd;
      transition: all 0.3s ease-in-out;
    }

    .input-group select:focus,
    .input-group input:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      top: 12px;
      right: 12px;
      color: #007bff;
      font-size: 16px;
    }

    .submit-group {
      display: flex;
      justify-content: center;
    }

    .submit-group button {
      padding: 12px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .submit-group button:hover {
      background-color: #0056b3;
    }

    .submit-group button i {
      font-size: 18px;
    }

    .category-stats-container {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }

    .category-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      padding: 20px;
      min-width: 200px;
      flex: 1;
      max-width: 240px;
      border-left: 5px solid var(--accent-color, #1f4f65);
      transition: transform 0.2s;
    }

    .category-card:hover {
      transform: translateY(-4px);
    }

    .card-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-text h4 {
      margin: 0;
      color: #5f6b7a;
      font-size: 14px;
    }

    .card-text .count {
      font-size: 24px;
      font-weight: bold;
      color: #2c3e50;
      margin: 5px 0 0 0;
    }

    .card-icon {
      font-size: 24px;
      color: #ccc;
    }
  </style>
</head>

<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <a href="../../FrontOffice/index.php" class="logo-link">
          <div class="logo">
            <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
            <span>Transit</span><span class="highlight">X</span>
          </div>
        </a>
        <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
      </div>

      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li><a href="../users/crud.php"><i class="fas fa-users"></i><span>Utilisateurs</span></a></li>
            <li><a href="../bus/crud.php"><i class="fas fa-bus"></i><span>Bus</span></a></li>
            <li><a href="../trajets/crud.php"><i class="fas fa-road"></i><span>Trajets</span></a></li>
            <li><a href="../colis/crud.php"><i class="fas fa-box"></i><span>Colis</span></a></li>
            <li><a href="../reclamations/crud.php"><i
                  class="fas fa-exclamation-circle"></i><span>Réclamations</span></a></li>
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
        <form action="afficher_articles.php" method="GET" class="header-right">
          <div class="search-bar">
            <input type="text" name="searchTerm" placeholder="Rechercher un article..." required>
            <button type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>

        </form>

      </header>
      <div class="category-stats-container" style="margin-left: 40px;">
        <?php foreach ($categoryStats as $stat): ?>
          <div class="category-card" style="--accent-color: <?= getCategoryColor($stat['categorie']) ?>;">
            <div class="card-content">
              <div class="card-text">
                <h4><?= htmlspecialchars($stat['categorie']) ?></h4>
                <p class="count"><?= htmlspecialchars($stat['count']) ?></p>
              </div>
              <div class="card-icon">
                <i class="<?= getCategoryIcon($stat['categorie']) ?>"></i>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>





      <script>
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: <?php echo json_encode($categories); ?>,
            datasets: [{
              label: 'Nombre d\'articles',
              data: <?php echo json_encode($counts); ?>,
              backgroundColor: '#1f4f65',
              borderRadius: 8
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  stepSize: 1
                }
              }
            }
          }
        });
      </script>
      <section class="form-section">
        <h2>Ajouter un Article</h2>
        <form id="articleForm" action="addarticle.php" method="POST" enctype="multipart/form-data"
          class="form-container" onsubmit="return validateForm();">
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
              <label for="categorie">Catégorie :</label>
              <select id="categorie" name="categorie" class="form-input" required>
                <option value="">-- Sélectionnez une catégorie --</option>
                <option value="Conseils de voyage">Conseils de voyage</option>
                <option value="Sécurité">Sécurité</option>
                <option value="Économie et écologie">Économie et écologie</option>
                <option value="Autre">Autre</option>

              </select>
              <span class="error-message" id="categorie-error"></span>
            </div>

            <div class="form-group">
              <label for="auteur">auteur :</label>
              <input type="text" id="auteur" name="auteur" class="form-input" />
              <span class="error-message" id="auteur-error"></span>
            </div>
            <div class="form-group">
              <label for="tags">Tags (séparés par des virgules) :</label>
              <input type="text" id="tags" name="tags" class="form-input"
                placeholder="Exemple : Mobilité, Innovation" />
              <span class="error-message" id="tags-error"></span>
            </div>
            <div class="form-group">
              <label for="photo">Photo :</label>
              <input type="file" id="photo" name="photo" accept="image/*" class="form-input" />
              <span class="error-message" id="photo-error"></span>
            </div>

          </fieldset>
          <button type="submit" class="btn">Ajouter</button>
        </form>


        <form action="searchArticles.php" method="GET">
          <label for="article">Choisir un article :</label>
          <select name="article" id="article">
            <?php
            try {
              $pdo = new PDO('mysql:host=localhost;dbname=transitx', 'root', '');
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $query = $pdo->query("SELECT * FROM article");
              $articles = $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
              echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            foreach ($articles as $article) {
              echo "<option value='" . $article['id_article'] . "'>" . $article['titre'] . "</option>";
            }
            ?>
          </select>
          <button type="submit">Afficher les commentaires</button>
        </form>

        <form method="get" action="" class="filtre-form">
          <div class="input-group">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie">
              <option value="">Toutes</option>
              <option value="Conseils de voyage">Conseils de voyage</option>
              <option value="Sécurité">Sécurité</option>
              <option value="Économie et écologie">Économie et écologie</option>
            </select>
          </div>

          <div class="input-group">
            <label for="auteur">Auteur :</label>
            <div class="input-wrapper">
              <input type="text" name="auteur" id="auteur" placeholder="Filtrer par auteur" />
              <i class="fas fa-user"></i> <!-- Icone de recherche -->
            </div>
          </div>

          <div class="submit-group">
            <button type="submit">
              <i class="fas fa-filter"></i> Filtrer
            </button>
          </div>
        </form>
        <!-- Bouton pour afficher les statistiques -->
        <div
          style="margin-top: 20px; padding: 20px; border-radius: 16px; background: #ffffff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); max-width: 480px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <h3 style="margin: 0; color: #1e3a5f; font-size: 18px;">📊 Statistiques des articles</h3>
            <button id="showStatsBtn" title="Afficher les articles les plus commentés"
              style="background-color: #1e88e5; color: white; border: none; border-radius: 8px; padding: 8px 12px; font-size: 14px; cursor: pointer;">
              <i class="fas fa-chart-pie"></i>
            </button>
          </div>
          <div id="statsContainer" style="display: none; margin-top: 20px;">
            <canvas id="statsChart" style="width: 100%; max-width: 420px;"></canvas>
          </div>
        </div>


        <!-- Conteneur pour afficher les statistiques (initialement caché) -->
        <div id="statsContainer" style="display:none;">
          <canvas id="statsChart" style="max-width: 400px; max-height: 400px;"></canvas>
        </div>

        <script>
          document.getElementById("showStatsBtn").addEventListener("click", function () {
            document.getElementById("statsContainer").style.display = "block";
            displayStats();
          });

          function displayStats() {
            // Exemple de données des articles les plus commentés, récupérées de PHP
            const topArticles = <?php echo json_encode($topArticles); ?>; // Assure-toi que $topArticles est bien défini en PHP

            // Calcul des pourcentages
            const totalComments = topArticles.reduce((sum, article) => sum + parseInt(article.nb_commentaires), 0);
            const data = topArticles.map(article => (parseInt(article.nb_commentaires) / totalComments) * 100);

            // Labels des articles
            const labels = topArticles.map(article => article.titre);

            // Création du graphique
            const ctx = document.getElementById("statsChart").getContext("2d");
            const chart = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: labels,
                datasets: [{
                  label: 'Pourcentage des commentaires',
                  data: data,
                  backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FF9F40'], // Couleurs pour chaque segment
                  borderColor: '#fff',
                  borderWidth: 1
                }]
              },
              options: {
                responsive: true,
                plugins: {
                  tooltip: {
                    callbacks: {
                      label: function (tooltipItem) {
                        const percentage = tooltipItem.raw.toFixed(2) + "%";
                        return `${tooltipItem.label}: ${percentage}`;
                      }
                    }
                  }
                }
              }
            });
          }
        </script>


        <!-- Tableau stylisé -->
        <div class="buses-table-container">
          <table class="buses-table">
            <thead>
              <tr>
                <th>ID Article</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>auteur</th>
                <th>
                  Date Publication
                  <a href="?sort=date_asc" title="Trier du plus ancien au plus récent">
                    <i class="fa-solid fa-arrow-up-wide-short"></i>
                  </a>
                  <a href="?sort=date_desc" title="Trier du plus récent au plus ancien">
                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                  </a>
                </th>
                <th>categorie</th>
                <th>tags</th>
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
                  <td><?= htmlspecialchars($offer['auteur']); ?></td>

                  <td><?= htmlspecialchars($offer['date_publication']); ?></td>
                  <td><?= htmlspecialchars($offer['categorie'] ?? '') ?></td>
                  <td><?= htmlspecialchars($offer['tags'] ?? '') ?></td>


                  <td>
                    <?php if (!empty($offer['photo'])): ?>
                      <img src="../../assets/uploads/<?= htmlspecialchars($offer['photo']); ?>" alt="Photo Article"
                        style="width: 80px;">
                    <?php else: ?>
                      Aucune image
                    <?php endif; ?>
                  </td>
                  <td class="actions">
                    <a href="updatearticle.php?id=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn edit"
                      title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="deletearticle.php?id_article=<?= htmlspecialchars($offer['id_article']); ?>"
                      class="action-btn delete" title="Supprimer">
                      <i class="fas fa-trash-alt"></i>
                    </a>

                    <!-- Nouveau lien pour exporter en PDF -->
                    <a href="export_pdf.php?id=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn"
                      title="Exporter en PDF">
                      <i class="fas fa-file-pdf"></i>
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
      const categorie = document.getElementById("categorie").value;
      if (categorie === "") {
        document.getElementById("categorie-error").textContent = "La catégorie est requise.";
        valid = false;
      }

      // Validation de la photo
      const photo = document.getElementById("photo").value;
      if (photo === "") {
        document.getElementById("photo-error").textContent = "La photo est requise.";
        valid = false;
      }
      const auteur = document.getElementById("auteur").value;
      if (auteur === "") {
        document.getElementById("auteur-error").textContent = "L'auteur est requis.";
        valid = false;
      }
      const tags = document.getElementById("tags").value;
      const tagsPattern = /^#\w+(?:,\s*#\w+)*$/; // Expression régulière pour valider les tags

      if (tags === "") {
        document.getElementById("tags-error").textContent = "Les tags sont requis.";
        valid = false;
      } else if (!tagsPattern.test(tags)) {
        document.getElementById("tags-error").textContent = "Les tags doivent commencer par un '#' et être séparés par des virgules.";
        valid = false;
      } else {
        document.getElementById("tags-error").textContent = ""; // Si valide, on supprime le message d'erreur
      }
      return valid;
    }
  </script>
</body>

</html>