<?php
include(__DIR__ . "/../config.php");
include(__DIR__ . "/../Model/busmodel.php");

class BusController
{
    private $db;

    public function __construct()
    {
        $this->db = config::getConnexion();
    }

    public function listBuses()
    {
        $sql = "SELECT * FROM bus";
        try {
            return $this->db->query($sql);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addBus(Bus $bus)
    {
        $sql = "INSERT INTO bus (id_trajet, num_bus, capacite, type_bus, marque, modele, date_mise_en_service, statut) 
                VALUES (:id_trajet, :num_bus, :capacite, :type_bus, :marque, :modele, :date_mise_en_service, :statut)";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_trajet', $bus->getIdTrajet());
            $query->bindValue(':num_bus', $bus->getNumBus());
            $query->bindValue(':capacite', $bus->getCapacite());
            $query->bindValue(':type_bus', $bus->getTypeBus());
            $query->bindValue(':marque', $bus->getMarque());
            $query->bindValue(':modele', $bus->getModele());
            $query->bindValue(':date_mise_en_service', $bus->getDateMiseEnService());
            $query->bindValue(':statut', $bus->getStatut());
            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateBus(Bus $bus)
    {
        $sql = "UPDATE bus SET 
                id_trajet = :id_trajet, 
                num_bus = :num_bus, 
                capacite = :capacite,
                type_bus = :type_bus, 
                marque = :marque,
                modele = :modele,
                date_mise_en_service = :date_mise_en_service,
                statut = :statut
                WHERE id_bus = :id_bus";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_bus', $bus->getIdBus());
            $query->bindValue(':id_trajet', $bus->getIdTrajet());
            $query->bindValue(':num_bus', $bus->getNumBus());
            $query->bindValue(':capacite', $bus->getCapacite());
            $query->bindValue(':type_bus', $bus->getTypeBus());
            $query->bindValue(':marque', $bus->getMarque());
            $query->bindValue(':modele', $bus->getModele());
            $query->bindValue(':date_mise_en_service', $bus->getDateMiseEnService());
            $query->bindValue(':statut', $bus->getStatut());
            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteBus($id_bus)
    {
        $sql = "DELETE FROM bus WHERE id_bus = :id_bus";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_bus', $id_bus);
            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
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
    public function getAllTrajets()
    {
        $sql = "SELECT * FROM trajet";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des trajets: " . $e->getMessage());
        }
    }


}
?>