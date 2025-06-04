<?php
session_start();
require_once __DIR__ . '/../../../config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: todo.php');
    exit;
}

$id_tache = $_GET['id'];
$id_utilisateur = $_SESSION['user_id'];

$sql = "DELETE FROM taches WHERE id = ? AND id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_tache, $id_utilisateur]);

header('Location: todo.php');
exit;
