<?php
try {
    require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
} catch (Exception $e) {
    echo 'Erreur lors de l\'inclusion du fichier : ' . $e->getMessage();
}


$articc = new ArticleC();  
$list = $articc->listoffre();
?>
<html>
    <head>
        <title>Afficher un article</title>
    </head>
    <body>
        <table border="1">
            <tr>
                <th>id_article</th>
                <th>titre</th>
                <th>contenu</th>
                <th>date_publication</th>
                <th>Actions</th>  
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
        </table>
    </body>
</html>
