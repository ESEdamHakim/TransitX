<?php
require_once __DIR__ . '/../../../Controller/commentaireC.php';

if (isset($_GET['id_article'])) {
    $commentaireC = new CommentaireC();
    $comments = $commentaireC->afficherCommentaires($_GET['id_article']);
    if ($comments && count($comments) > 0) {
        echo '<div class="comments-list">';
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '  <div class="comment-content">';
            echo '    <span class="comment-text">' . htmlspecialchars($comment['contenu_commentaire']) . '</span>';
            echo '    <small class="comment-date">' . htmlspecialchars($comment['date_commentaire']) . '</small>';
            echo '  </div>';
            echo '  <button class="delete-comment-btn" data-comment-id="' . htmlspecialchars($comment['id_commentaire']) . '" title="Supprimer ce commentaire">';
            echo '    <i class="fas fa-trash"></i>';
            echo '  </button>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="no-comments">Aucun commentaire pour cet article.</p>';
    }
} else {
    echo '<p class="error-message">Erreur : article non spécifié.</p>';
}
?>