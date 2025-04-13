<?php
    require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
    $articc = new ArticleC();
        $articc->deleteOffer($_GET['id_article']);
        
        header("Location: crud.php");
       
?>
