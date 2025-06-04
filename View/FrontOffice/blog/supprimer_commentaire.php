<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if (isset($_POST['id_commentaire']) && isset($_POST['id_article'])) {
    $id = intval($_POST['id_commentaire']);
    $id_article = intval($_POST['id_article']);

    $deleteRepliesStmt = $pdo->prepare("DELETE FROM commentaire WHERE id_parent = ?");
    $deleteRepliesStmt->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = ?");
    $stmt->execute([$id]);

header("Location: blog-detail.php?id=$id_article");
    exit;
} else {
    echo "Paramètres manquants.";
}

?>
