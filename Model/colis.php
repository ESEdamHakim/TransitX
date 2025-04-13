<?php
class colis {
    private $id_colis;
    private $adresse;
    private $statut;
    private $date_colis;
    private $largeur;
    private $hauteur;
    private $poids;
    private $latitude_dest;
    private $longitude_dest;
    private $prix; // Add prix property

    public function __construct($id_colis, $adresse, $statut, $date_colis, $largeur, $hauteur, $poids, $latitude_dest, $longitude_dest, $prix) {
        $this->id_colis = $id_colis;
        $this->adresse = $adresse;
        $this->statut = $statut;
        $this->date_colis = $date_colis;
        $this->largeur = $largeur;
        $this->hauteur = $hauteur;
        $this->poids = $poids;
        $this->latitude_dest = $latitude_dest;
        $this->longitude_dest = $longitude_dest;
        $this->prix = $prix; // Initialize prix in the constructor
    }

    public function getIdColis() {
        return $this->id_colis;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getDateColis() {
        return $this->date_colis;
    }

    public function getLargeur() {
        return $this->largeur;
    }

    public function getHauteur() {
        return $this->hauteur;
    }

    public function getPoids() {
        return $this->poids;
    }

    public function getLatitudeDest() {
        return $this->latitude_dest;
    }

    public function getLongitudeDest() {
        return $this->longitude_dest;
    }

    public function getPrix() { // Add getter for prix
        return $this->prix;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function setDateColis($date_colis) {
        $this->date_colis = $date_colis;
    }

    public function setLargeur($largeur) {
        $this->largeur = $largeur;
    }

    public function setHauteur($hauteur) {
        $this->hauteur = $hauteur;
    }

    public function setPoids($poids) {
        $this->poids = $poids;
    }

    public function setLatitudeDest($latitude_dest) {
        $this->latitude_dest = $latitude_dest;
    }

    public function setLongitudeDest($longitude_dest) {
        $this->longitude_dest = $longitude_dest;
    }

    public function setPrix($prix) { // Add setter for prix
        $this->prix = $prix;
    }
}
?>
