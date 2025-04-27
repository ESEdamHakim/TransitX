<?php
if (isset($_GET['article'])) {
    $id_article = $_GET['article'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=transitx', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // On récupère l'article sélectionné
        $stmtArticle = $pdo->prepare("SELECT * FROM article WHERE id_article = ?");
        $stmtArticle->execute([$id_article]);
        $article = $stmtArticle->fetch(PDO::FETCH_ASSOC);

        // On récupère les commentaires liés à cet article
        $stmtCommentaires = $pdo->prepare("SELECT * FROM commentaire WHERE id_article = ?");
        $stmtCommentaires->execute([$id_article]);
        $commentaires = $stmtCommentaires->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }
} else {
    echo "Aucun article sélectionné.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Commentaires de l'article</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      width: 80%;
      margin: 0 auto;
      padding: 20px;
      background-color: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    h2 {
      color: #2c3e50;
      font-size: 24px;
      margin-bottom: 20px;
    }

    .article-title {
      font-size: 28px;
      color: #2980b9;
      margin-bottom: 20px;
    }

    .comment-list {
      list-style-type: none;
      padding: 0;
    }

    .comment {
      background-color: #ecf0f1;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
    }

    .comment-header {
      font-weight: bold;
      color: #34495e;
    }

    .comment-content {
      margin-top: 10px;
      color: #7f8c8d;
    }

    .comment-actions {
      margin-top: 10px;
    }

    .comment-actions a {
      color: #e74c3c;
      text-decoration: none;
      font-weight: bold;
    }

    .comment-actions a:hover {
      text-decoration: underline;
    }

    .no-comments {
      color: #95a5a6;
      font-style: italic;
    }

    .form-comment {
      margin-top: 30px;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-comment textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      resize: vertical;
    }

    .form-comment button {
      background-color: #2980b9;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    .form-comment button:hover {
      background-color: #3498db;
    }
  </style>
</head>
<body>

  <div class="container">
    <?php if (!empty($article)) : ?>
      <div class="article">
        <h1 class="article-title"><?= htmlspecialchars($article['titre']) ?></h1>
      </div>

      <h2>Commentaires :</h2>

      <?php if (count($commentaires) > 0): ?>
        <ul class="comment-list">
          <?php foreach ($commentaires as $commentaire): ?>
            <li class="comment">
              <div class="comment-header">
              </div>
              <p class="comment-content"><?= nl2br(htmlspecialchars($commentaire['contenu_commentaire'])) ?></p>
              <div class="comment-actions">
                <a href="supprimer_commentaire.php?id_commentaire=<?= $commentaire['id_commentaire'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">Supprimer</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="no-comments">Aucun commentaire pour cet article.</p>
      <?php endif; ?>


    <?php else: ?>
      <p>Aucun article sélectionné.</p>
    <?php endif; ?>
  </div>

</body>
</html>
