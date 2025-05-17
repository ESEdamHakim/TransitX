<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$id = intval($_GET['id']);
$action = $_GET['action'];

if ($action == 'like') {
    $stmt = $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes + 1 WHERE id_commentaire = ?");
    $stmt->execute([$id]);
} elseif ($action == 'dislike') {
    $stmt = $pdo->prepare("UPDATE commentaire SET nb_dislikes = nb_dislikes + 1 WHERE id_commentaire = ?");
    $stmt->execute([$id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Action invalide.']);
    exit;
}

// Récupérer les nouveaux totaux
$stmt = $pdo->prepare("SELECT nb_likes, nb_dislikes FROM commentaire WHERE id_commentaire = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'nb_likes' => $row['nb_likes'],
    'nb_dislikes' => $row['nb_dislikes']
]);
exit;