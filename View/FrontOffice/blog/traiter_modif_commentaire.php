<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_commentaire']);
    $contenu = trim($_POST['contenu_commentaire']);

    if (empty($contenu)) {
        echo "Le contenu ne peut pas être vide.";
        exit;
    }

    $stmt = $pdo->prepare("UPDATE commentaire SET contenu_commentaire = ? WHERE id_commentaire = ?");
    $stmt->execute([$contenu, $id]);

    $id_article = $_POST['id_article']; 
header("Location: blog-detail.php?id=$id_article");
    exit;
    
}
?>
