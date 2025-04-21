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
</head>
<body>
  <?php if (!empty($article)) : ?>
    <h2>Commentaires pour : <?= htmlspecialchars($article['titre']) ?></h2>

    <?php if (count($commentaires) > 0): ?>
      <ul>
      <?php foreach ($commentaires as $commentaire): ?>
            <tr>
                <td><?= htmlspecialchars($commentaire['id_article']) ?></td>
                <td><?= nl2br(htmlspecialchars($commentaire['contenu_commentaire'])) ?></td>
                <td><?= $commentaire['date_commentaire'] ?></td>
                <td>
                <a href="supprimer_commentaire.php?id_commentaire=<?= $commentaire['id_commentaire'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
    Supprimer
</a>

                </td>
            </tr>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Aucun commentaire pour cet article.</p>
    <?php endif; ?>
  <?php endif; ?>
</body>
</html>
