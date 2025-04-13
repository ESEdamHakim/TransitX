<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

// Requête pour récupérer tous les articles
$stmt = $pdo->query("SELECT * FROM article ORDER BY date_publication DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner le résultat en JSON
header('Content-Type: application/json');
echo json_encode($articles);
?>
