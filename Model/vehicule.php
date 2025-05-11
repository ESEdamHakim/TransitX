<?php
class Vehicule
{
    // Attributes corresponding to the table columns
    private $id_vehicule;
    private $matricule;
    private $type_vehicule;
    private $nb_places;
    private $couleur;
    private $marque;
    private $modele;
    private $confort;
    private $photo_vehicule;
    private $id_user; // Foreign key referencing the users table

    // Constructor
    public function __construct(
        $matricule = null,
        $type_vehicule = null,
        $nb_places = null,
        $couleur = null,
        $marque = null,
        $modele = null,
        $confort = null,
        $photo_vehicule = null,
        $id_user = null
    ) {
        $this->matricule = $matricule;
        $this->type_vehicule = $type_vehicule;
        $this->nb_places = $nb_places;
        $this->couleur = $couleur;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->confort = $confort;
        $this->photo_vehicule = $photo_vehicule;
        $this->id_user = $id_user;
    }

    // Getters
    public function getIdVehicule()
    {
        return $this->id_vehicule;
    }

    public function getMatricule()
    {
        return $this->matricule;
    }

    public function getTypeVehicule()
    {
        return $this->type_vehicule;
    }

    public function getNbPlaces()
    {
        return $this->nb_places;
    }

    public function getCouleur()
    {
        return $this->couleur;
    }

    public function getMarque()
    {
        return $this->marque;
    }

    public function getModele()
    {
        return $this->modele;
    }

    public function getConfort()
    {
        return $this->confort;
    }

    public function getPhotoVehicule()
    {
        return $this->photo_vehicule;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    // Setters
    public function setIdVehicule($id_vehicule)
    {
        $this->id_vehicule = $id_vehicule;
    }

    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
    }

    public function setTypeVehicule($type_vehicule)
    {
        $this->type_vehicule = $type_vehicule;
    }

    public function setNbPlaces($nb_places)
    {
        $this->nb_places = $nb_places;
    }

    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    public function setMarque($marque)
    {
        $this->marque = $marque;
    }

    public function setModele($modele)
    {
        $this->modele = $modele;
    }

    public function setConfort($confort)
    {
        $this->confort = $confort;
    }

    public function setPhotoVehicule($photo_vehicule)
    {
        $this->photo_vehicule = $photo_vehicule;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }
}
?>