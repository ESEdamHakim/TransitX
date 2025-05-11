<?php
class Trajet
{
    private $id_trajet;
    private $place_depart;
    private $place_arrivee;
    private $heure_depart;
    private $duree;
    private $distance_km;
    private $prix;

    public function __construct($place_depart, $place_arrivee, $heure_depart, $duree, $distance_km, $prix, $id_trajet = null)
    {
        $this->id_trajet = $id_trajet;
        $this->place_depart = $place_depart;
        $this->place_arrivee = $place_arrivee;
        $this->heure_depart = $heure_depart;
        $this->duree = $duree;
        $this->distance_km = $distance_km;
        $this->prix = $prix;
    }

    // Getters
    public function getIdTrajet() { return $this->id_trajet; }
    public function getPlaceDepart() { return $this->place_depart; }
    public function getPlaceArrivee() { return $this->place_arrivee; }
    public function getHeureDepart() { return $this->heure_depart; }
    public function getDuree() { return $this->duree; }
    public function getDistanceKm() { return $this->distance_km; }
    public function getPrix() { return $this->prix; }

    // Setters
    public function setIdTrajet($id_trajet) { $this->id_trajet = $id_trajet; }
    public function setPlaceDepart($place_depart) { $this->place_depart = $place_depart; }
    public function setPlaceArrivee($place_arrivee) { $this->place_arrivee = $place_arrivee; }
    public function setHeureDepart($heure_depart) { $this->heure_depart = $heure_depart; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setDistanceKm($distance_km) { $this->distance_km = $distance_km; }
    public function setPrix($prix) { $this->prix = $prix; }
}
?>
