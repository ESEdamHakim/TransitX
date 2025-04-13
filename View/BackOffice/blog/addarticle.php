<?php
    require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $date_publication = $_POST['date_publication'];
    

    $articc = new ArticleC();  
    $articc->addOffre($titre, $contenu, $date_publication);

    echo "Offre ajoutée avec succès.";
}
?>

<html>


</body>
</html>

