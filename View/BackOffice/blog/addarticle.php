<?php
    require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
    require_once __DIR__ . '/../../../Model/BackOffice/Article.php';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        $date_publication = $_POST['date_publication'];
        $photoName = null;
    
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoTmpPath = $_FILES['photo']['tmp_name'];
            $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
            $uploadDir = '../../../uploads/';
            move_uploaded_file($photoTmpPath, $uploadDir . $photoName);
        }
    
        $article = new Article($titre, $contenu, $date_publication, $photoName);
        $articleC = new ArticleC();
        $articleC->addarticle($article);
        
        header("Location: crud.php");
        exit;
    }
    
?>

<html>


</body>
</html>

