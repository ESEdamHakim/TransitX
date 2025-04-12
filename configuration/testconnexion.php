<?php
require_once "config.php";

try {
    $pdo = config::getConnexion();
    echo "✅ Connexion réussie à la base de données TransitX !";
} catch (Exception $e) {
    echo "❌ Connexion échouée : " . $e->getMessage();
}
?>
