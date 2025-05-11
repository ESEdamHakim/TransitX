<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Model/trajetmodel.php");

class TrajetController
{
    public $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    public function listTrajets()
    {
        $sql = "SELECT * FROM trajet";
        try {
            // Use fetchAll to retrieve all rows as an associative array
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetches all rows as an associative array
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
    public function getBusesByTrajetId($id_trajet, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM bus WHERE id_trajet = ?");
        $stmt->execute([$id_trajet]);
        $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
        foreach ($buses as &$bus) {
          $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM bus_reservation WHERE id_bus = ? AND id_user = ?");
          $stmt2->execute([$bus['id_bus'], $user_id]);
          $bus['reserved'] = $stmt2->fetchColumn() > 0;
        }
      
        return $buses;
      }      

    public function getBusById($id_bus)
    {
        $sql = "SELECT * FROM bus WHERE id_bus = :id_bus";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_bus', $id_bus);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function isTrajetFavori($id_trajet, $user_id) {
        $stmt = $this->db->prepare("SELECT 1 FROM bus_favoris WHERE user_id = ? AND id_trajet = ?");
        $stmt->execute([$user_id, $id_trajet]);
        return $stmt->fetch() !== false;
      }
      public function addFavori($user_id, $id_trajet) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO bus_favoris (user_id, id_trajet) VALUES (?, ?)");
        $stmt->execute([$user_id, $id_trajet]);
    }
    
    public function removeFavori($user_id, $id_trajet) {
        $stmt = $this->db->prepare("DELETE FROM bus_favoris WHERE user_id = ? AND id_trajet = ?");
        $stmt->execute([$user_id, $id_trajet]);
    }
    public function getFavorisByUserId($user_id) {
        $sql = "SELECT t.* 
                FROM bus_favoris bf
                INNER JOIN trajet t ON bf.id_trajet = t.id_trajet
                WHERE bf.user_id = :user_id";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      
}
?>
