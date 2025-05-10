<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

// Vérification de l'existence de l'article
$id_commentaire = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'epingle') {
    // Épingler le commentaire
    $stmt = $pdo->prepare("UPDATE commentaire SET epingle = 1 WHERE id_commentaire = ?");
    $stmt->execute([$id_commentaire]);
} elseif ($action === 'desepingle') {
    // Désépingler le commentaire
    $stmt = $pdo->prepare("UPDATE commentaire SET epingle = 0 WHERE id_commentaire = ?");
    $stmt->execute([$id_commentaire]);
}

header("Location: blog-detail.php?id=" . $_GET['id_article']); // Redirection vers la page de l'article
exit;
