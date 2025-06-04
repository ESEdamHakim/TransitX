<?php
require_once __DIR__ . '/../config.php';


class CommentaireC {
    public function afficherCommentaires($idArticle) {
        $sql = "SELECT * FROM commentaire WHERE id_article = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $idArticle);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
        public function supprimerCommentaire($idCommentaire) {
        $sql = "DELETE FROM commentaire WHERE id_commentaire = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $idCommentaire);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>