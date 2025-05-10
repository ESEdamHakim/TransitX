<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_article = intval($_POST['id_article']);
    $contenu_commentaire = trim($_POST['contenu_commentaire']);

    if (empty($contenu_commentaire)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO commentaire (id_article, contenu_commentaire, date_commentaire) VALUES (?, ?, NOW())");
    $stmt->execute([$id_article, $contenu_commentaire]);

    header("Location: article.php?id=" . $id_article);
    exit;
}
?>
