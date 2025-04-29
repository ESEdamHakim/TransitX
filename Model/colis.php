<?php
class Colis
{
    private $id_colis;
    private $id_client;
    private $id_covoit;
    private $statut;
    private $date_colis;
    private $longueur;
    private $largeur;
    private $hauteur;
    private $poids;
    private $lieu_ram;
    private $lieu_dest;
    private $latitude_ram;
    private $longitude_ram;
    private $latitude_dest;
    private $longitude_dest;
    private $prix;

    public function __construct($id_colis, $id_client, $id_covoit, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $lieu_ram, $lieu_dest, $latitude_ram, $longitude_ram, $latitude_dest, $longitude_dest, $prix)
    {
        $this->id_colis = $id_colis;
        $this->id_client = $id_client;
        $this->id_covoit = $id_covoit;
        $this->statut = $statut;
        $this->date_colis = $date_colis;
        $this->longueur = $longueur;
        $this->largeur = $largeur;
        $this->hauteur = $hauteur;
        $this->poids = $poids;
        $this->lieu_ram = $lieu_ram;
        $this->lieu_dest = $lieu_dest;
        $this->latitude_ram = $latitude_ram;
        $this->longitude_ram = $longitude_ram;
        $this->latitude_dest = $latitude_dest;
        $this->longitude_dest = $longitude_dest;
        $this->prix = $prix;
    }

    // Getters
    public function getIdColis()
    {
        return $this->id_colis;
    }
    public function getIdClient()
    {
        return $this->id_client;
    }
    public function getIdCovoit()
    {
        return $this->id_covoit;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getDateColis()
    {
        return $this->date_colis;
    }
    public function getLongueur()
    {
        return $this->longueur;
    }
    public function getLargeur()
    {
        return $this->largeur;
    }
    public function getHauteur()
    {
        return $this->hauteur;
    }
    public function getPoids()
    {
        return $this->poids;
    }
    public function getLieuRam()
    {
        return $this->lieu_ram;
    }
    public function getLieuDest()
    {
        return $this->lieu_dest;
    }
    public function getLatitudeRam()
    {
        return $this->latitude_ram;
    }
    public function getLongitudeRam()
    {
        return $this->longitude_ram;
    }
    public function getLatitudeDest()
    {
        return $this->latitude_dest;
    }
    public function getLongitudeDest()
    {
        return $this->longitude_dest;
    }
    public function getPrix()
    {
        return $this->prix;
    }

    // Setters
    public function setIdClient($id_client)
    {
        $this->id_client = $id_client;
    }
    public function setIdCovoit($id_covoit)
    {
        $this->id_covoit = $id_covoit;
    }
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }
    public function setDateColis($date_colis)
    {
        $this->date_colis = $date_colis;
    }
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;
    }
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;
    }
    public function setHauteur($hauteur)
    {
        $this->hauteur = $hauteur;
    }
    public function setPoids($poids)
    {
        $this->poids = $poids;
    }
    public function setLieuRam($lieu_ram)
    {
        $this->lieu_ram = $lieu_ram;
    }
    public function setLieuDest($lieu_dest)
    {
        $this->lieu_dest = $lieu_dest;
    }
    public function setLatitudeRam($latitude_ram)
    {
        $this->latitude_ram = $latitude_ram;
    }
    public function setLongitudeRam($longitude_ram)
    {
        $this->longitude_ram = $longitude_ram;
    }
    public function setLatitudeDest($latitude_dest)
    {
        $this->latitude_dest = $latitude_dest;
    }
    public function setLongitudeDest($longitude_dest)
    {
        $this->longitude_dest = $longitude_dest;
    }
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }
}
?>