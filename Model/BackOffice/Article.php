<?php
class Article {
    private $id_article;
    private $titre;
    private $contenu;
    private $date_publication;
    private $photo;
    private $auteur; // Ajout de l'attribut auteur

    // Modifie le constructeur pour inclure l'attribut auteur
    public function __construct($titre, $contenu, $date_publication, $photo, $auteur, $id_article = null) {
        $this->id_article = $id_article;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
        $this->photo = $photo;
        $this->auteur = $auteur; // Assigne la valeur de l'auteur
    }

    // Getter pour l'id_article
    public function getIdarticle() {
        return $this->id_article;
    }

    // Getter pour le titre
    public function getTitre() {
        return $this->titre;
    }

    // Getter pour le contenu
    public function getContenu() {
        return $this->contenu;
    }

    // Getter pour la date de publication
    public function getDatepublication() {
        return $this->date_publication;
    }

    // Getter pour la photo
    public function getPhoto() {
        return $this->photo;
    }

    // Getter pour l'auteur
    public function getAuteur() {
        return $this->auteur; // Retourne l'auteur de l'article
    }

    // Setter pour l'id_article
    public function setIdarticle($id_article) {
        $this->id_article = $id_article;
    }

    // Setter pour le titre
    public function setTitre($titre) {
        $this->titre = $titre;
    }

    // Setter pour le contenu
    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    // Setter pour la date de publication
    public function setDate_publication($date_publication) {
        $this->date_publication = $date_publication;
    }

    // Setter pour la photo
    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    // Setter pour l'auteur
    public function setAuteur($auteur) {
        $this->auteur = $auteur; // Modifie l'auteur de l'article
    }
}
?>
