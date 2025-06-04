<?php
session_start();
require_once __DIR__ . '/../../../config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: todo.php');
    exit;
}

$id_tache = $_GET['id'];
$id_utilisateur = $_SESSION['user_id'];

// Récupérer le statut actuel
$sql = "SELECT statut FROM taches WHERE id = ? AND id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_tache, $id_utilisateur]);
$tache = $stmt->fetch();

if ($tache) {
    $nouveau_statut = $tache['statut'] === 'en_cours' ? 'terminee' : 'en_cours';

    $sql_update = "UPDATE taches SET statut = ? WHERE id = ? AND id_utilisateur = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->execute([$nouveau_statut, $id_tache, $id_utilisateur]);
}

header('Location: todo.php');
exit;
