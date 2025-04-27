<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contenu = $_POST["comment"] ?? '';
    $id_article = $_POST["id_article"] ?? 0;
    $id_user = $_POST["id_user"] ?? null;
    $id_parent = isset($_POST["id_parent"]) && $_POST["id_parent"] !== "NULL" ? intval($_POST["id_parent"]) : null;

    if (!empty($contenu) && $id_article > 0 && $id_user !== null) {
        $stmt = $pdo->prepare("INSERT INTO commentaire (id_article, id_user, contenu_commentaire, date_commentaire, id_parent) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->execute([$id_article, $id_user, $contenu, $id_parent]);
    }

    header("Location: blog-detail.php?id=" . $id_article);
    exit;
}

?>
