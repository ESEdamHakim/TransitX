<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$stmt = $pdo->query("SELECT * FROM article ORDER BY date_publication DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($articles);
?>
