<?php
include_once(__DIR__ . "/../config.php");
include(__DIR__ . "/../Model/trajetmodel.php");

class TrajetController
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    public function listTrajets()
    {
        $sql = "SELECT * FROM trajet";
        try {
            return $this->db->query($sql);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addTrajet(Trajet $trajet)
    {
        $sql = "INSERT INTO trajet (place_depart, place_arrivee, heure_depart, duree, distance_km, prix) 
                VALUES (:place_depart, :place_arrivee, :heure_depart, :duree, :distance_km, :prix)";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':place_depart', $trajet->getPlaceDepart());
            $query->bindValue(':place_arrivee', $trajet->getPlaceArrivee());
            $query->bindValue(':heure_depart', $trajet->getHeureDepart());
            $query->bindValue(':duree', $trajet->getDuree());
            $query->bindValue(':distance_km', $trajet->getDistanceKm());
            $query->bindValue(':prix', $trajet->getPrix());
            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateTrajet(Trajet $trajet)
    {
        $sql = "UPDATE trajet SET 
                place_depart = :place_depart, 
                place_arrivee = :place_arrivee,
                heure_depart = :heure_depart,
                duree = :duree,
                distance_km = :distance_km,
                prix = :prix
                WHERE id_trajet = :id_trajet";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_trajet', $trajet->getIdTrajet());
            $query->bindValue(':place_depart', $trajet->getPlaceDepart());
            $query->bindValue(':place_arrivee', $trajet->getPlaceArrivee());
            $query->bindValue(':heure_depart', $trajet->getHeureDepart());
            $query->bindValue(':duree', $trajet->getDuree());
            $query->bindValue(':distance_km', $trajet->getDistanceKm());
            $query->bindValue(':prix', $trajet->getPrix());
            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteTrajet($id_trajet)
    {
        $sql = "DELETE FROM trajet WHERE id_trajet = :id_trajet";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_trajet', $id_trajet);
            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getTrajetById($id_trajet)
    {
        $sql = "SELECT * FROM trajet WHERE id_trajet = :id_trajet";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_trajet', $id_trajet);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>
