<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Étape 1 : Récupérer tous les articles avec l'auteur et le nombre de commentaires
$query = "SELECT a.id_article, a.titre, a.contenu, a.photo, a.auteur, 
                 COUNT(c.id_commentaire) AS nb_commentaires 
          FROM article a 
          LEFT JOIN commentaire c ON a.id_article = c.id_article 
          GROUP BY a.id_article 
          ORDER BY a.date_publication DESC";
$stmt = $pdo->query($query);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Étape 2 : Formatage des résultats
foreach ($articles as &$article) {
    // Nettoyer le nom de l'auteur, si nécessaire
    $article['auteur'] = htmlspecialchars($article['auteur']);
    // Ajouter l'icône de l'auteur (ici un exemple avec 'fa-user')
    $article['auteur_icon'] = 'fa-user';  // Utiliser l'icône utilisateur de Font Awesome
    // Ajouter le nombre de commentaires
    $article['nb_commentaires'] = (int)$article['nb_commentaires'];
}

// Étape 3 : Retourner les articles au format JSON
header('Content-Type: application/json');
echo json_encode($articles);
?>
