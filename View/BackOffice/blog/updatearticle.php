<?php 
    require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
    require_once __DIR__ . '/../../../Model/BackOffice/Article.php';

// Vérifiez si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    // Récupérer l'ID de l'offre
    $id_article = $_GET['id'];

    // Créez une instance de ArticleC
    $articc = new ArticleC();  // Corrige la casse de la classe

    // Récupérer les détails de l'offre pour remplir le formulaire
    $offer = $articc->getOfferById($id_article);

    // Si l'offre existe
    if ($offer) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Si le formulaire est soumis, mettez à jour l'offre
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $date_publication = $_POST['date_publication'];

            // Créez un objet offre avec les nouvelles données
            $updatedOffer = new Article($titre, $contenu, $date_publication, $id_article);

            // Mettez à jour l'offre
            $articc->updateOffre($updatedOffer);

            // Message de succès (affiché avant la redirection)
            echo "L'offre a été mise à jour avec succès.";

            // Redirigez après la mise à jour
            header("Location: crud.php");
            exit();
        }
    } else {
        echo "L'offre n'a pas été trouvée.";
    }
} else {
    echo "ID de l'offre manquant.";
}
?>

<html>
<head>
    <title>Update Offer</title>
</head>
<body>
    <h1>Update Offer</h1>
    <form method="POST" action="">
        <label for="titre">Titre:</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($offer['titre']) ?>" required><br>

        <label for="contenu">contenu:</label>
        <input type="text" name="contenu" id="contenu" value="<?= htmlspecialchars($offer['contenu']) ?>" required><br>

        <label for="date_publication">date_publication</label>
        <input type="date" name="date_publication" id="date_publication" value="<?= htmlspecialchars($offer['date_publication']) ?>" required><br>

        <input type="submit" value="Update Offer">
    </form>
</body>
</html>
