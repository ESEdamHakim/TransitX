<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
        <p>Type d'utilisateur: <?= htmlspecialchars($_SESSION['user_type']) ?></p>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
</body>
</html>
