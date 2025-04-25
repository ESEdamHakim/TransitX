<?php
require_once __DIR__ . '/../config.php';

class ReclamationController
{
    public function listReclamation()
    {
        $sql = "SELECT * FROM reclamation";
        $db = config::getConnexion();
        try {
            return $db->query($sql);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addReclamation($id_client, $id_covoit, $objet, $description, $date_rec, $statut)
    {
        $sql = "INSERT INTO reclamation (id_client, id_covoit, objet, description, date_rec, statut)
                VALUES (:id_client, :id_covoit, :objet, :description, :date_rec, :statut)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_client', $id_client);
            $query->bindValue(':id_covoit', $id_covoit);
            $query->bindValue(':objet', $objet);
            $query->bindValue(':description', $description);
            $query->bindValue(':date_rec', $date_rec);
            $query->bindValue(':statut', $statut);
            $query->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateReclamation($id_rec, $id_client, $id_covoit, $objet, $description, $date_rec, $statut)
    {
        $sql = "UPDATE reclamation SET 
                id_client = :id_client,
                id_covoit = :id_covoit,
                objet = :objet,
                description = :description,
                date_rec = :date_rec,
                statut = :statut
                WHERE id_rec = :id_rec";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_rec', $id_rec);
            $query->bindValue(':id_client', $id_client);
            $query->bindValue(':id_covoit', $id_covoit);
            $query->bindValue(':objet', $objet);
            $query->bindValue(':description', $description);
            $query->bindValue(':date_rec', $date_rec);
            $query->bindValue(':statut', $statut);
            $query->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteReclamation($id_rec)
    {
        $sql = "DELETE FROM reclamation WHERE id_rec = :id_rec";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_rec', $id_rec);
            $query->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function getAllCovoiturages()
    {
        $sql = "SELECT id_covoit, lieu_depart, lieu_arrivee FROM covoiturage";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching covoiturages: ' . $e->getMessage());
        }
    }

    public function getAllClients()
    {
        $sql = "SELECT id_user, nom, prenom FROM users"; // Adjust table name if needed
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching clients: ' . $e->getMessage());
        }
    }
    public function getClientById($id_user)
    {
        $sql = "SELECT id_user, nom, prenom FROM users WHERE id_user = :id_user";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_user', $id_user);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching client: ' . $e->getMessage());
        }
    }

    public function getCovoiturageById($id_covoit)
    {
        $sql = "SELECT id_covoit, lieu_depart, lieu_arrivee FROM covoiturage WHERE id_covoit = :id_covoit";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_covoit', $id_covoit);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching covoiturage: ' . $e->getMessage());
        }
    }

}
?>