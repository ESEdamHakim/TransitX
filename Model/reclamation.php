<?php
class reclamation {
    private $id_rec;
    private $objet;
    private $description;
    private $date_rec;
    private $statut;

    public function __construct($id_rec, $objet, $description, $date_rec, $statut) {
        $this->id_rec = $id_rec;
        $this->objet = $objet;
        $this->description = $description;
        $this->date_rec = $date_rec;
        $this->statut = $statut;
    }

    public function getIdRec() {
        return $this->id_rec;
    }

    public function getObjet() {
        return $this->objet;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDateRec() {
        return $this->date_rec;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function setObjet($objet) {
        $this->objet = $objet;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDateRec($date_rec) {
        $this->date_rec = $date_rec;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }
}
?>
