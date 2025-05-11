<?php
class colis {
    private $id_colis;
    private $statut;
    private $date_colis;
    private $longueur;  // Added 'longueur' property
    private $largeur;
    private $hauteur;
    private $poids;
    private $latitude_ram;
    private $longitude_ram;
    private $latitude_dest;
    private $longitude_dest;
    private $prix;

    public function __construct($id_colis, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $latitude_ram, $longitude_ram, $latitude_dest, $longitude_dest, $prix) {
        $this->id_colis = $id_colis;
        $this->statut = $statut;
        $this->date_colis = $date_colis;
        $this->longueur = $longueur;  // Initialize 'longueur'
        $this->largeur = $largeur;
        $this->hauteur = $hauteur;
        $this->poids = $poids;
        $this->latitude_ram = $latitude_ram;
        $this->longitude_ram = $longitude_ram;
        $this->latitude_dest = $latitude_dest;
        $this->longitude_dest = $longitude_dest;
        $this->prix = $prix;
    }

    public function getIdColis() {
        return $this->id_colis;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getDateColis() {
        return $this->date_colis;
    }

    public function getLongueur() {  // Getter for 'longueur'
        return $this->longueur;
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

    public function getLatitudeRam() {
        return $this->latitude_ram;
    }

    public function getLongitudeRam() {
        return $this->longitude_ram;
    }

    public function getLatitudeDest() {
        return $this->latitude_dest;
    }

    public function getLongitudeDest() {
        return $this->longitude_dest;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function setDateColis($date_colis) {
        $this->date_colis = $date_colis;
    }

    public function setLongueur($longueur) {  // Setter for 'longueur'
        $this->longueur = $longueur;
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

    public function setLatitudeRam($latitude_ram) {
        $this->latitude_ram = $latitude_ram;
    }

    public function setLongitudeRam($longitude_ram) {
        $this->longitude_ram = $longitude_ram;
    }

    public function setLatitudeDest($latitude_dest) {
        $this->latitude_dest = $latitude_dest;
    }

    public function setLongitudeDest($longitude_dest) {
        $this->longitude_dest = $longitude_dest;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }
}
?>
