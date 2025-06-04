<?php
require_once __DIR__ . '/../../../Controller/commentaireC.php';

if (isset($_POST['id_commentaire'])) {
    $commentaireC = new CommentaireC();
    $result = $commentaireC->supprimerCommentaire($_POST['id_commentaire']);
    echo $result ? 'success' : 'error';
} else {
    echo 'error';
}
?>