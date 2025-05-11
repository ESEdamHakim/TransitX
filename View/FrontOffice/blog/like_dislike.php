<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$id = intval($_GET['id']);
$action = $_GET['action']; 

if ($action == 'like') {
    $stmt = $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes + 1 WHERE id_commentaire = ?");
} elseif ($action == 'dislike') {
    $stmt = $pdo->prepare("UPDATE commentaire SET nb_dislikes = nb_dislikes + 1 WHERE id_commentaire = ?");
} else {
    exit("Action invalide.");
}

$stmt->execute([$id]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
