<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contenu = $_POST["comment"] ?? '';
    $id_article = $_POST["id_article"] ?? 0;
    $id_user = $_POST["id_user"] ?? null;
    $id_parent = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : null;

    if (!empty($contenu) && $id_article > 0 && $id_user !== null) {
        $stmt = $pdo->prepare("INSERT INTO commentaire (id_article, id_user, contenu_commentaire, id_parent, date_commentaire) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$id_article, $id_user, $contenu, $id_parent]);
        
    }

    header("Location: blog-detail.php?id=" . $id_article);
    exit;
}

?>