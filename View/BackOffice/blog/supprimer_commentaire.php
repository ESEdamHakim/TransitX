<?php
    require_once __DIR__ . '/../../../config.php'; 

if (isset($_GET['id_commentaire'])) {
    $id = $_GET['id_commentaire'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");
        $stmt = $pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = ?");
        $stmt->execute([$id]);

header("Location: /searchArticles.php");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID de commentaire manquant.";
}
?>
