<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contenu = $_POST["comment"] ?? '';
    $id_article = $_POST["id_article"] ?? 0;

    if (!empty($contenu) && $id_article > 0) {
        $stmt = $pdo->prepare("INSERT INTO commentaire (id_article, contenu_commentaire, date_commentaire) VALUES (?, ?, NOW())");
        $stmt->execute([$id_article, $contenu]);
    }

    header("Location: blog-detail.php?id=" . $id_article);
    exit;
}
?>
