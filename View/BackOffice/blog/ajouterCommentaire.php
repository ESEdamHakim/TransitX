<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

// Vérification si la méthode est POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et validation des données
    $id_article = intval($_POST['id_article']);
    $contenu_commentaire = trim($_POST['contenu_commentaire']);

    // Validation simple : vérifier que le contenu n'est pas vide
    if (empty($contenu_commentaire)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    // Préparation et exécution de la requête pour ajouter le commentaire
    $stmt = $pdo->prepare("INSERT INTO commentaire (id_article, contenu_commentaire, date_commentaire) VALUES (?, ?, NOW())");
    $stmt->execute([$id_article, $contenu_commentaire]);

    // Redirection après l'insertion
    header("Location: article.php?id=" . $id_article);
    exit;
}
?>
