<?php
class Commentaire {
    private $id_commentaire;
    private $id_article;
    private $contenu_commentaire;
    private $date_commentaire;

    public function __construct($id_article, $contenu_commentaire, $date_commentaire, $id_commentaire = null) {
        $this->id_article = $id_article;
        $this->contenu_commentaire = $contenu_commentaire;
        $this->date_commentaire = $date_commentaire;
        $this->id_commentaire = $id_commentaire;
    }

    public function getIdCommentaire() {
        return $this->id_commentaire;
    }

    public function getIdArticle() {
        return $this->id_article;
    }

    public function getContenuCommentaire() {
        return $this->contenu_commentaire;
    }

    public function getDateCommentaire() {
        return $this->date_commentaire;
    }

    public function setIdCommentaire($id_commentaire) {
        $this->id_commentaire = $id_commentaire;
    }

    public function setIdArticle($id_article) {
        $this->id_article = $id_article;
    }

    public function setContenuCommentaire($contenu_commentaire) {
        $this->contenu_commentaire = $contenu_commentaire;
    }

    public function setDateCommentaire($date_commentaire) {
        $this->date_commentaire = $date_commentaire;
    }
}
?>
