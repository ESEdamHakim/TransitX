<?php
require_once __DIR__ . '/../../../Controller/commentaireC.php';

if (isset($_GET['id_article'])) {
    $commentaireC = new CommentaireC();
    $comments = $commentaireC->afficherCommentaires($_GET['id_article']);
    if ($comments && count($comments) > 0) {
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<strong>' . htmlspecialchars($comment['auteur']) . ':</strong> ';
            echo '<span>' . htmlspecialchars($comment['contenu']) . '</span>';
            echo '<br><small>' . htmlspecialchars($comment['date_commentaire']) . '</small>';
            echo '</div><hr>';
        }
    } else {
        echo '<p>Aucun commentaire pour cet article.</p>';
    }
} else {
    echo '<p>Erreur : article non spécifié.</p>';
}
?>