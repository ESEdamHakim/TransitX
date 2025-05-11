<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT a.id_article, a.titre, a.contenu, a.photo, a.auteur, a.categorie, a.tags, 
                 COUNT(c.id_commentaire) AS nb_commentaires 
          FROM article a 
          LEFT JOIN commentaire c ON a.id_article = c.id_article 
          GROUP BY a.id_article 
          ORDER BY a.date_publication DESC";
$stmt = $pdo->query($query);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($articles as &$article) {
    $article['auteur'] = htmlspecialchars($article['auteur']);
    $article['auteur_icon'] = 'fa-user';  
    $article['nb_commentaires'] = (int)$article['nb_commentaires'];
}

header('Content-Type: application/json');
echo json_encode($articles);
?>
