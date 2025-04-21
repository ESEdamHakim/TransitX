<?php 
class Article {
    private $id_article ;
    private $titre;
    private $contenu;
    private $date_publication;
    private $photo;
   

    public function __construct($titre, $contenu, $date_publication, $photo, $id_article = null) {
        $this->id_article = $id_article;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
        $this->photo= $photo;
    }

   
    public function getIdarticle() {
        return $this->id_article;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getDatepublication() {
        return $this->date_publication;
    }
    public function getPhoto() {
        return $this->photo;
    }

    
    public function setIdarticle($id_article) {
        $this->id_article = $id_article;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function setDate_publication($date_publication) {
        $this->date_publication = $date_publication;
    }
    public function setPhoto($photo) {
        $this->photo = $photo;
    }
}
?>
