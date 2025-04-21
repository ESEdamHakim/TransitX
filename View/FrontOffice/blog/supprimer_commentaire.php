<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if (isset($_GET['id_commentaire']) && isset($_GET['id_article'])) {
    $id = intval($_GET['id_commentaire']);
    $id_article = intval($_GET['id_article']);

    $stmt = $pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = ?");
    $stmt->execute([$id]);

    // Redirection vers la page de l'article
    header("Location: /TransitX-main/View/FrontOffice/blog/blog-detail.php?id=$id_article");
    exit;
} else {
    echo "Paramètres manquants.";
}
?>
