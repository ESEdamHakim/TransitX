<?php
require_once __DIR__ . '/../../../Controller/ArticleC.php';
    $articc = new ArticleC();
        $articc->deletearticle($_GET['id_article']);
        
        header("Location: crud.php");
       
?>
