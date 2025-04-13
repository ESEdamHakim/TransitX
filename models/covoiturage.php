<?php
class Covoiturage
{
    // Attributes corresponding to the table columns
    private $id_covoit;
    private $date_depart;
    private $lieu_depart;
    private $lieu_arrivee;
    private $accepte_colis;
    private $colis_complet;
    private $details;
    private $prix;
    private $temps_depart;
    private $places_dispo;

    // Constructor
    public function __construct(
        $date_depart = null,
        $lieu_depart = null,
        $lieu_arrivee = null,
        $accepte_colis = null,
        $colis_complet = null,
        $details = null,
        $prix = null,
        $temps_depart = null,
        $places_dispo = null
    ) {
        $this->date_depart = $date_depart;
        $this->lieu_depart = $lieu_depart;
        $this->lieu_arrivee = $lieu_arrivee;
        $this->accepte_colis = $accepte_colis;
        $this->colis_complet = $colis_complet;
        $this->details = $details;
        $this->prix = $prix;
        $this->temps_depart = $temps_depart;
        $this->places_dispo = $places_dispo;
    }

    // Getters
    public function getIdCovoit()
    {
        return $this->id_covoit;
    }

    public function getDateDepart()
    {
        return $this->date_depart;
    }

    public function getLieuDepart()
    {
        return $this->lieu_depart;
    }

    public function getLieuArrivee()
    {
        return $this->lieu_arrivee;
    }

    public function getAccepteColis()
    {
        return $this->accepte_colis;
    }

    public function getColisComplet()
    {
        return $this->colis_complet;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function getTempsDepart()
    {
        return $this->temps_depart;
    }

    public function getPlacesDispo()
    {
        return $this->places_dispo;
    }

    // Setters
    public function setIdCovoit($id_covoit)
    {
        $this->id_covoit = $id_covoit;
    }

    public function setDateDepart($date_depart)
    {
        $this->date_depart = $date_depart;
    }

    public function setLieuDepart($lieu_depart)
    {
        $this->lieu_depart = $lieu_depart;
    }

    public function setLieuArrivee($lieu_arrivee)
    {
        $this->lieu_arrivee = $lieu_arrivee;
    }

    public function setAccepteColis($accepte_colis)
    {
        $this->accepte_colis = $accepte_colis;
    }

    public function setColisComplet($colis_complet)
    {
        $this->colis_complet = $colis_complet;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function setTempsDepart($temps_depart)
    {
        $this->temps_depart = $temps_depart;
    }

    public function setPlacesDispo($places_dispo)
    {
        $this->places_dispo = $places_dispo;
    }
}
?>