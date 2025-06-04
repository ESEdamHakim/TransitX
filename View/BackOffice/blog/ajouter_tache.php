<?php
session_start();
require_once __DIR__ . '/../../../config.php';

if (!isset($_SESSION['user_id']) || empty($_POST['contenu'])) {
    header('Location: todo.php');
    exit;
}

$contenu = htmlspecialchars(trim($_POST['contenu']));
$id_utilisateur = $_SESSION['user_id'];

$sql = "INSERT INTO taches (id_utilisateur, contenu) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_utilisateur, $contenu]);

header('Location: todo.php');
exit;
