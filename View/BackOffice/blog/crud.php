<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../Controller/ArticleC.php';
require_once __DIR__ . '/../../../Controller/UserC.php';

$userController = new UserC();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// For testing - use the first user from the list instead of session user
// Comment this out once testing is complete
$currentUser = null;
$currentUser = null;

if (isset($_SESSION['user_id'])) {
  $currentUser = $userController->showUser($_SESSION['user_id']);
}


$articleC = new ArticleC();
$list = $articleC->listarticle();



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
  <link rel="stylesheet" href="../../assets/css/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
          <link rel="stylesheet" href="../../assets/messagerie/messagerie.css">


</head>

<body>
    <?php include '../../assets/chatbot/chatbot.php'; ?>
          <?php include '../../assets/messagerie/messagerie.php'; ?>


  <div class="dashboard">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion du Blog</h1>
          <p>Ajoutez, modifiez et supprimez des articles de blog</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Rechercher par titre ou auteur"
              aria-label="Rechercher un article">
            <button><i class="fas fa-search"></i></button>
          </div>
          <button id="open-chart-modal" class="btn primary" style="font-size: 1.1rem; height: 45px;">
            <i class="fas fa-chart-pie"></i>
            Statistiques
          </button>
          <div class="actions">
            <a href="addarticle.php" class="btn primary"><i class="fas fa-plus"></i> Ajouter un Article</a>
            <div class="actions-container">
              <?php include '../assets/php/profile.php'; ?>
            </div>
          </div>
        </div>
      </header>
      <div class="dashboard-content">
        <!-- Stats Overview -->
        <div class="dashboard-stats">
          <div class="stat-box standard">
            <div class="stat-title">Conseils de voyage</div>
            <div class="stat-value" id="standardCount">0</div>
            <div class="stat-icon"><i class="fas fa-bus"></i></div>
          </div>
          <div class="stat-box tourisme">
            <div class="stat-title">Sécurité</div>
            <div class="stat-value" id="tourismeCount">0</div>
            <div class="stat-icon"><i class="fas fa-bus-alt"></i></div>
          </div>
          <div class="stat-box scolaire">
            <div class="stat-title">Économie et écologie</div>
            <div class="stat-value" id="scolaireCount">0</div>
            <div class="stat-icon"><i class="fas fa-school"></i></div>
          </div>
          <div class="stat-box autre">
            <div class="stat-title">Autre</div>
            <div class="stat-value" id="autreCount">0</div>
            <div class="stat-icon"><i class="fas fa-ellipsis-h"></i></div>
          </div>
        </div>

        <div class="crud-container">
          <!-- Tabs -->
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Articles</button>
              <button class="tab-btn" data-tab="Conseils de voyage">Conseils de voyage</button>
              <button class="tab-btn" data-tab="Sécurité">Sécurité</button>
              <button class="tab-btn" data-tab="Économie et écologie">Économie et écologie</button>
              <button class="tab-btn" data-tab="Autre">Autre</button>
            </div>
          </div>

          <!-- Article Table -->
          <div class="view-container table-view active">
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
                      <td><?= htmlspecialchars(substr($offer['titre'], 0, 30)); ?></td>
                      <td><?= htmlspecialchars(substr($offer['contenu'], 0, 60)) ?>...</td>
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
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                          <!-- Top row: Edit & Delete -->
                          <div style="display: flex; gap: 10px;">
                            <a href="updatearticle.php?id=<?= htmlspecialchars($offer['id_article']); ?>"
                              class="action-btn edit" title="Modifier">
                              <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="action-btn delete open-delete-modal" title="Supprimer"
                              data-id="<?= htmlspecialchars($offer['id_article']) ?>">
                              <i class="fas fa-trash"></i>
                            </button>
                            <!-- View Button to open modal -->
                            <button type="button" class="action-btn view open-content-modal"
                              data-titre="<?= htmlspecialchars($offer['titre']); ?>"
                              data-contenu="<?= htmlspecialchars($offer['contenu']); ?>"
                              data-auteur="<?= htmlspecialchars($offer['auteur']); ?>"
                              data-date="<?= htmlspecialchars($offer['date_publication']); ?>"
                              data-categorie="<?= htmlspecialchars($offer['categorie']); ?>"
                              data-tags="<?= htmlspecialchars($offer['tags']); ?>"
                              data-photo="<?= htmlspecialchars($offer['photo']); ?>" title="Voir le contenu complet">
                              <i class="fas fa-eye"></i>
                            </button>
                          </div>
                          <!-- Bottom row: PDF, Comments & View -->
                          <div style="display: flex; gap: 10px;">
                            <a href="export_pdf.php?id=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn"
                              title="Exporter en PDF" style="color: red;">
                              <i class="fas fa-file-pdf"></i>
                            </a>
                            <button type="button" class="action-btn comments-btn"
                              data-article-id="<?= htmlspecialchars($offer['id_article']); ?>"
                              title="Voir les commentaires" style="color: #4d7aa3;">
                              <i class="fas fa-comments"></i>
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </main>
  </div>
  <!-- Modal -->
  <div class="delete-modal" id="delete-modal">
    <div class="delete-modal-content">
      <div class="delete-modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="delete-modal-body">
        <p>Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>

    <!-- Hidden form for deletion -->
    <form method="POST" action="deletearticle.php" style="display: none;" id="delete-form">
      <input type="hidden" name="id_article" id="delete-id">
    </form>
  </div>
  <!-- View Modal -->
  <div id="content-modal" class="modal">
    <div class="modal-content">
      <div class="modal-headerr">
        <h2>Détails de l'article</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="article-modal-photo" id="modalPhoto"></div>
        <div class="article-meta-grid">
          <div><i class="fas fa-heading"></i> <span id="modalTitre"></span></div>
          <div><i class="fas fa-user"></i> <span id="modalAuteur"></span></div>
          <div><i class="fas fa-calendar-alt"></i> <span id="modalDate"></span></div>
          <div><i class="fas fa-folder"></i> <span id="modalCategorie"></span></div>
          <div><i class="fas fa-tags"></i> <span id="modalTags"></span></div>
        </div>
        <div class="article-content-section">
          <h3><i class="fas fa-align-left"></i> Contenu</h3>
          <div id="modalContentText"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- Comments Modal -->
  <div id="comments-modal" class="modal">
    <div class="modal-content">
      <div class="modal-headerr">
        <h2>Commentaires de l'article</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div id="comments-list">

        </div>
      </div>
    </div>
  </div>
  <!-- Delete Comment Confirmation Modal -->
  <div class="delete-modal" id="delete-comment-modal">
    <div class="delete-modal-content">
      <div class="delete-modal-header">
        <h2>Confirmer la suppression du commentaire</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="delete-modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-comment-btn">Supprimer</button>
        </div>
      </div>
    </div>
    <!-- Hidden form for deletion (optional, but not needed with AJAX) -->
    <input type="hidden" id="delete-comment-id">
  </div>

  <!-- Chart Modal -->
  <div id="chart-modal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
      <div class="modal-header">
        <h2>Répartition des commentaires (%)</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <canvas id="commentsChart" width="400" height="400"></canvas>
      </div>
    </div>
  </div>
  <?php include '../assets/php/profileManage.php'; ?>
  <script>
    // Pass PHP data to JS
    const articlesWithComments = <?php
    // Get articles and their comment counts
    $articlesData = [];
    foreach ($list as $article) {
      $articlesData[] = [
        'titre' => $article['titre'],
        'id_article' => $article['id_article'],
        'comment_count' => $articleC->getCommentCountByArticle($article['id_article'])
      ];
    }
    echo json_encode($articlesData);
    ?>;
  </script>
  <script src="assets/js/main.js"></script>
    <script src="../../assets/messagerie/messagerie.js"> </script>
  <script src="../assets/js/profile.js"></script>
  <script src="assets/js/profileManage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>

</body>

</html>