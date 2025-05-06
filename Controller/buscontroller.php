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
    public function getBusByIdObject($id_bus)
    {
        $sql = "SELECT * FROM bus WHERE id_bus = :id_bus";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_bus', $id_bus);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $bus = new Bus(
                    $row['id_trajet'],
                    $row['num_bus'],
                    $row['capacite'],
                    $row['type_bus'],
                    $row['marque'],
                    $row['modele'],
                    $row['date_mise_en_service'],
                    $row['statut']
                );
                $bus->setIdBus($row['id_bus']);
                return $bus;
            }

            return null;
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
    public function notifyUsersAboutNewBus($id_trajet, $id_bus)
    {
        // Step 1: Get all bus info
        $busQuery = $this->db->prepare("SELECT * FROM bus WHERE id_bus = :id_bus");
        $busQuery->execute(['id_bus' => $id_bus]);
        $bus = $busQuery->fetch(PDO::FETCH_ASSOC);
    
        if (!$bus)
            return;
    
        // Step 2: Get trajet details (departure, arrival, and time)
        $trajetQuery = $this->db->prepare("SELECT place_depart, place_arrivee, heure_depart FROM trajet WHERE id_trajet = :id_trajet");
        $trajetQuery->execute(['id_trajet' => $id_trajet]);
        $trajet = $trajetQuery->fetch(PDO::FETCH_ASSOC);
    
        if (!$trajet)
            return;
    
        // Step 3: Get users who favorited this trajet
        $query = $this->db->prepare("SELECT user_id AS id_user FROM bus_favoris WHERE id_trajet = :id_trajet");
        $query->execute(['id_trajet' => $id_trajet]);
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$users)
            return;
    
        // Step 4: Create a well-formatted, brief message
        $message = "🚌 Nouveau bus disponible sur votre trajet favori: ";
        $message .= "{$trajet['place_depart']} → {$trajet['place_arrivee']} à {$trajet['heure_depart']} !\n\n";
        $message .= "• Statut : {$bus['statut']}\n";
        $message .= "• Numéro : {$bus['num_bus']}\n";
        $message .= "• Type : {$bus['type_bus']}\n";
        $message .= "• Marque : {$bus['marque']}\n";
        $message .= "• Modèle : {$bus['modele']}\n";
        $message .= "• Capacité : {$bus['capacite']} personnes\n";
        $message .= "• Mise en service : {$bus['date_mise_en_service']}\n";
    
        // Trim the message to remove any leading or trailing whitespace
        $message = trim($message);
    
        // Step 5: Insert notifications
        foreach ($users as $user) {
            $insert = $this->db->prepare("INSERT INTO bus_notification (id_user, message) VALUES (:id_user, :message)");
            $insert->execute([
                'id_user' => $user['id_user'],
                'message' => $message
            ]);
        }
    }
    


    public function getNotificationsForUser($id_user)
    {
        $query = $this->db->prepare("SELECT * FROM bus_notification WHERE id_user = :id_user ORDER BY created_at DESC");
        $query->execute(['id_user' => $id_user]);
        return $query->fetchAll();
    }
    public function getEmailsByTrajet($id_trajet)
{
    $query = $this->db->prepare("
        SELECT u.email
        FROM bus_favoris bf
        JOIN users u ON bf.user_id = u.id_user
        WHERE bf.id_trajet = :id_trajet
    ");
    $query->execute(['id_trajet' => $id_trajet]);
    return $query->fetchAll(PDO::FETCH_COLUMN); // Returns array of emails
}


public function notifyUsersByEmail($id_trajet, $id_bus)
{
    // Step 1: Get all bus info
    $busQuery = $this->db->prepare("SELECT * FROM bus WHERE id_bus = :id_bus");
    $busQuery->execute(['id_bus' => $id_bus]);
    $bus = $busQuery->fetch(PDO::FETCH_ASSOC);

    if (!$bus)
        return;

    // Step 2: Get trajet details (departure, arrival, and time)
    $trajetQuery = $this->db->prepare("SELECT place_depart, place_arrivee, heure_depart FROM trajet WHERE id_trajet = :id_trajet");
    $trajetQuery->execute(['id_trajet' => $id_trajet]);
    $trajet = $trajetQuery->fetch(PDO::FETCH_ASSOC);

    if (!$trajet)
        return;

    // Step 3: Get emails of users who favorited this trajet
    $emails = $this->getEmailsByTrajet($id_trajet); // Using the getEmailsByTrajet function

    if (empty($emails))
        return;

    // Step 4: Create a well-formatted, brief message
    $subject  = "Nouveau bus disponible sur votre trajet favori: ";
    $subject  .= "{$trajet['place_depart']} vers {$trajet['place_arrivee']} a {$trajet['heure_depart']} !\n\n";
    $body = "• Statut : {$bus['statut']}\n";
    $body .= "• Numéro : {$bus['num_bus']}\n";
    $body .= "• Type : {$bus['type_bus']}\n";
    $body .= "• Marque : {$bus['marque']}\n";
    $body .= "• Modèle : {$bus['modele']}\n";
    $body .= "• Capacité : {$bus['capacite']} personnes\n";
    $body .= "• Mise en service : {$bus['date_mise_en_service']}\n";

    // Step 5: Send the email to each user
    foreach ($emails as $email) {
        sendBusEmailNotification($email, $subject, $body); // Send email to each user
    }
}

}
?>