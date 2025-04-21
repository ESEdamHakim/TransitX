<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$id = intval($_GET['id']);

// Récupération du commentaire
$stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire = ?");
$stmt->execute([$id]);
$commentaire = $stmt->fetch();

if (!$commentaire) {
    echo "Commentaire introuvable.";
    exit;
}
?>

<form method="POST" action="/TransitX-main/Controller/FrontOffice/traiter_modif_commentaire.php">
    <input type="hidden" name="id_commentaire" value="<?php echo $commentaire['id_commentaire']; ?>">
    <input type="hidden" name="id_article" value="<?php echo $commentaire['id_article']; ?>">
    <textarea name="contenu_commentaire"><?php echo htmlspecialchars($commentaire['contenu_commentaire']); ?></textarea>
    <button type="submit">Enregistrer les modifications</button>
</form>
