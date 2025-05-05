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
        $bus->setNbPlacesDispo($bus->getCapacite());

        $sql = "INSERT INTO bus (id_trajet, num_bus, capacite, type_bus, marque, modele, date_mise_en_service, statut, nbplacesdispo) 
                VALUES (:id_trajet, :num_bus, :capacite, :type_bus, :marque, :modele, :date_mise_en_service, :statut, :nbplacesdispo)";
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
            $query->bindValue(':nbplacesdispo', $bus->getNbPlacesDispo());
            $query->execute();
            return $this->db->lastInsertId();
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
    public function notifyUsersAboutNewBus($id_trajet, $id_bus) {
        // Step 1: Get all bus info
        $busQuery = $this->db->prepare("SELECT * FROM bus WHERE id_bus = :id_bus");
        $busQuery->execute(['id_bus' => $id_bus]);
        $bus = $busQuery->fetch(PDO::FETCH_ASSOC);
    
        if (!$bus) {
            return; // no bus found
        }
    
        // Step 2: Get users who favorited this trajet
        $query = $this->db->prepare("SELECT user_id AS id_user FROM bus_favoris WHERE id_trajet = :id_trajet");
        $query->execute(['id_trajet' => $id_trajet]);
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$users) {
            return; // no users favorited this trajet
        }
    
        // Step 3: Build a full detailed message
        $message = "🚌 Nouveau bus ajouté au trajet ID: $id_trajet\n";
        $message .= "🆔 Numéro: {$bus['num_bus']}\n";
        $message .= "🚍 Type: {$bus['type_bus']}\n";
        $message .= "🏷️ Marque: {$bus['marque']}\n";
        $message .= "📦 Modèle: {$bus['modele']}\n";
        $message .= "👥 Capacité: {$bus['capacite']} personnes\n";
        $message .= "✅ Statut: {$bus['statut']}\n";
        $message .= "📅 Mise en service: {$bus['date_mise_en_service']}\n";
    
        // Step 4: Insert notification for each user
        foreach ($users as $user) {
            $insert = $this->db->prepare("INSERT INTO bus_notification (id_user, message) VALUES (:id_user, :message)");
            $insert->execute([
                'id_user' => $user['id_user'],
                'message' => $message
            ]);
        }
    }
    
    public function getNotificationsForUser($id_user) {
        $query = $this->db->prepare("SELECT * FROM bus_notification WHERE id_user = :id_user ORDER BY created_at DESC");
        $query->execute(['id_user' => $id_user]);
        return $query->fetchAll();
    }   

}
?>