<?php
require_once __DIR__ . '/../../../Controller/ArticleC.php';

if (isset($_POST["id_article"])) {
    $articc = new ArticleC();
    $articc->deletearticle($_POST['id_article']);
}

header("Location: crud.php");
exit();
?>
