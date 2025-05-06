<?php
// ColisController.php
require_once __DIR__ . '/../config.php';

class ColisController
{
    // Fetch all colis
    public function listColis()
    {
        $sql = "SELECT * FROM colis";
        $db = config::getConnexion();
        try {
            return $db->query($sql);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Add a new colis
    // Add a new colis and return the inserted ID
    public function addColis($id_client, $id_covoit, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $lieu_ram, $lieu_dest, $latitude_ram, $longitude_ram, $latitude_dest, $longitude_dest, $prix)
    {
        $db = config::getConnexion();

        try {
            $sql = "INSERT INTO colis (
                id_client, id_covoit, statut, date_colis, 
                longueur, largeur, hauteur, poids, 
                lieu_ram, lieu_dest,
                latitude_ram, longitude_ram, 
                latitude_dest, longitude_dest, prix
            ) VALUES (
                :id_client, :id_covoit, :statut, :date_colis, 
                :longueur, :largeur, :hauteur, :poids, 
                :lieu_ram, :lieu_dest,
                :latitude_ram, :longitude_ram, 
                :latitude_dest, :longitude_dest, :prix
            )";

            $query = $db->prepare($sql);
            $query->bindValue(':id_client', $id_client, PDO::PARAM_INT);
            $query->bindValue(':id_covoit', $id_covoit ?? null, $id_covoit === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $query->bindValue(':statut', $statut);
            $query->bindValue(':date_colis', $date_colis);
            $query->bindValue(':longueur', $longueur);
            $query->bindValue(':largeur', $largeur);
            $query->bindValue(':hauteur', $hauteur);
            $query->bindValue(':poids', $poids);
            $query->bindValue(':lieu_ram', $lieu_ram);
            $query->bindValue(':lieu_dest', $lieu_dest);
            $query->bindValue(':latitude_ram', $latitude_ram);
            $query->bindValue(':longitude_ram', $longitude_ram);
            $query->bindValue(':latitude_dest', $latitude_dest);
            $query->bindValue(':longitude_dest', $longitude_dest);
            $query->bindValue(':prix', $prix);

            $query->execute();

            // ✅ Return the last inserted ID
            return $db->lastInsertId();

        } catch (Exception $e) {
            die('Erreur SQL: ' . $e->getMessage());
        }
    }

    // Update an existing colis
    public function updateColis($id_colis, $id_client, $id_covoit, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $lieu_ram, $lieu_dest, $latitude_ram, $longitude_ram, $latitude_dest, $longitude_dest, $prix)
    {
        $db = config::getConnexion();

        try {
            $sql = "UPDATE colis SET 
                id_client = :id_client,
                id_covoit = :id_covoit,
                statut = :statut,
                date_colis = :date_colis,
                longueur = :longueur,
                largeur = :largeur,
                hauteur = :hauteur,
                poids = :poids,
                lieu_ram = :lieu_ram,
                lieu_dest = :lieu_dest,
                latitude_ram = :latitude_ram,
                longitude_ram = :longitude_ram,
                latitude_dest = :latitude_dest,
                longitude_dest = :longitude_dest,
                prix = :prix
                WHERE id_colis = :id_colis";

            $query = $db->prepare($sql);
            $query->bindValue(':id_colis', $id_colis);
            $query->bindValue(':id_client', $id_client);

            // Handle NULL for id_covoit
            if ($id_covoit === null) {
                $query->bindValue(':id_covoit', null, PDO::PARAM_NULL);
            } else {
                $query->bindValue(':id_covoit', $id_covoit, PDO::PARAM_INT);
            }

            $query->bindValue(':statut', $statut);
            $query->bindValue(':date_colis', $date_colis);
            $query->bindValue(':longueur', $longueur);
            $query->bindValue(':largeur', $largeur);
            $query->bindValue(':hauteur', $hauteur);
            $query->bindValue(':poids', $poids);
            $query->bindValue(':lieu_ram', $lieu_ram);
            $query->bindValue(':lieu_dest', $lieu_dest);
            $query->bindValue(':latitude_ram', $latitude_ram);
            $query->bindValue(':longitude_ram', $longitude_ram);
            $query->bindValue(':latitude_dest', $latitude_dest);
            $query->bindValue(':longitude_dest', $longitude_dest);
            $query->bindValue(':prix', $prix);

            $query->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Delete a colis
    public function deleteColis($id_colis)
    {
        $sql = "DELETE FROM colis WHERE id_colis = :id_colis";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_colis', $id_colis);
            return $query->execute();
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
        $sql = "SELECT id_user, nom, prenom, email FROM users WHERE id_user = :id_user";
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
        $sql = "SELECT id_covoit, lieu_depart, lieu_arrivee, id_user FROM covoiturage WHERE id_covoit = :id_covoit";
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
    public function getCovoituragesByUserId($id_user)
    {
        $sql = "SELECT id_covoit, lieu_depart, lieu_arrivee, id_user FROM covoiturage WHERE id_user = :id_user";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching covoiturages by user: ' . $e->getMessage());
        }
    }

    public function getColisByCovoiturage($id_user)
    {
        $covoiturages = $this->getCovoituragesByUserId($id_user); // <-- FIXED

        if (empty($covoiturages)) {
            return [];
        }

        $ids = array_column($covoiturages, 'id_covoit');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT * FROM colis WHERE id_covoit IN ($placeholders)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute($ids);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching colis by user: ' . $e->getMessage());
        }
    }
    public function getUserById($id_user)
    {
        $db = config::getConnexion(); // Get database connection

        try {
            // Prepare SQL query to get user details by user ID
            $sql = "SELECT * FROM users WHERE id_user = :id_user";
            $query = $db->prepare($sql);

            // Bind the ID parameter to the SQL query
            $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);

            // Execute the query
            $query->execute();

            // Fetch the user data
            $user = $query->fetch(PDO::FETCH_ASSOC);

            // Return the user details if found, or null if not found
            return $user ? $user : null;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage()); // Handle any potential errors
        }
    }
    public function getColisById($id_colis)
    {
        $sql = "SELECT * FROM colis WHERE id_colis = :id_colis";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_colis', $id_colis, PDO::PARAM_INT);
            $query->execute();

            // Fetch the colis by its ID
            $colis = $query->fetch(PDO::FETCH_ASSOC);

            // Return the colis if found, otherwise null
            return $colis ? $colis : null;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage()); // Handle any errors
        }
    }
    public function addNotification($id_colis, $id_sender, $id_reciever)
    {
        $sql = "INSERT INTO coliscovoit_notif (id_colis, id_sender, id_reciever)
                VALUES (:id_colis, :id_sender, :id_reciever)";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_colis' => $id_colis,
                'id_sender' => $id_sender,
                'id_reciever' => $id_reciever
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>