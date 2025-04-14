<?php
class Bus
{
    private $id_bus;
    private $id_trajet;
    private $num_bus;
    private $capacite;
    private $type_bus; 
    private $marque;
    private $modele;
    private $date_mise_en_service;
    private $statut;

    public function __construct($id_trajet, $num_bus, $capacite, $type_bus, $marque, $modele, $date_mise_en_service, $statut, $id_bus = null)
    {
        $this->id_bus = $id_bus;
        $this->id_trajet = $id_trajet;
        $this->num_bus = $num_bus;
        $this->capacite = $capacite;
        $this->type_bus = $type_bus; 
        $this->marque = $marque;
        $this->modele = $modele;
        $this->date_mise_en_service = $date_mise_en_service;
        $this->statut = $statut;
    }

    // Getters
    public function getIdBus() { return $this->id_bus; }
    public function getIdTrajet() { return $this->id_trajet; }
    public function getNumBus() { return $this->num_bus; }
    public function getCapacite() { return $this->capacite; }
    public function getTypeBus() { return $this->type_bus; } 
    public function getMarque() { return $this->marque; }
    public function getModele() { return $this->modele; }
    public function getDateMiseEnService() { return $this->date_mise_en_service; }
    public function getStatut() { return $this->statut; }

    // Setters
    public function setIdBus($id_bus) { $this->id_bus = $id_bus; }
    public function setIdTrajet($id_trajet) { $this->id_trajet = $id_trajet; }
    public function setNumBus($num_bus) { $this->num_bus = $num_bus; }
    public function setCapacite($capacite) { $this->capacite = $capacite; }
    public function setTypeBus($type_bus) { $this->type_bus = $type_bus; } 
    public function setMarque($marque) { $this->marque = $marque; }
    public function setModele($modele) { $this->modele = $modele; }
    public function setDateMiseEnService($date_mise_en_service) { $this->date_mise_en_service = $date_mise_en_service; }
    public function setStatut($statut) { $this->statut = $statut; }
}
?>
