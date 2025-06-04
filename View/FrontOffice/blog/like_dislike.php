<?php
require 'config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id_user = $_SESSION['id_user'] ?? null;
$id_commentaire = $_POST['id_commentaire'] ?? null;
$action = $_POST['action'] ?? null;

if (!$id_user || !$id_commentaire || !in_array($action, ['like', 'dislike'])) {
    echo json_encode(['success' => false]);
    exit;
}

// Vérifie s'il a déjà réagi
$stmt = $pdo->prepare("SELECT type FROM commentaire_reactions WHERE id_user = ? AND id_commentaire = ?");
$stmt->execute([$id_user, $id_commentaire]);
$existing = $stmt->fetchColumn();

if ($existing) {
    if ($existing === $action) {
        // Même réaction => l'enlever (toggle off)
        $stmt = $pdo->prepare("DELETE FROM commentaire_reactions WHERE id_user = ? AND id_commentaire = ?");
        $stmt->execute([$id_user, $id_commentaire]);

        // Décrémenter le compteur
        $champ = $action === 'like' ? 'nb_likes' : 'nb_dislikes';
        $pdo->prepare("UPDATE commentaire SET $champ = $champ - 1 WHERE id_commentaire = ?")->execute([$id_commentaire]);
    } else {
        // Réaction différente => modifier
        $stmt = $pdo->prepare("UPDATE commentaire_reactions SET type = ? WHERE id_user = ? AND id_commentaire = ?");
        $stmt->execute([$action, $id_user, $id_commentaire]);

        // Mettre à jour les compteurs
        $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes + ? , nb_dislikes = nb_dislikes + ? WHERE id_commentaire = ?")
            ->execute([
                $action === 'like' ? 1 : -1,
                $action === 'dislike' ? 1 : -1,
                $id_commentaire
            ]);
    }
} else {
    // Nouvelle réaction
    $stmt = $pdo->prepare("INSERT INTO commentaire_reactions (id_user, id_commentaire, type) VALUES (?, ?, ?)");
    $stmt->execute([$id_user, $id_commentaire, $action]);

    // Incrémenter le bon compteur
    $champ = $action === 'like' ? 'nb_likes' : 'nb_dislikes';
    $pdo->prepare("UPDATE commentaire SET $champ = $champ + 1 WHERE id_commentaire = ?")->execute([$id_commentaire]);
}

echo json_encode(['success' => true]);
