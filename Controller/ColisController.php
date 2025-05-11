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
    public function addColis($id_client, $id_covoit, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $latitude_ram, $longitude_ram, $latitude_dest, $longitude_dest, $prix)
    {
        $db = config::getConnexion();

        try {
            // Validate foreign key for client
            if (!$this->clientExists($id_client)) {
                throw new Exception("Client with ID $id_client does not exist.");
            }

            // Validate covoiturage existence if id_covoit is not NULL
            if ($id_covoit !== null && !$this->covoiturageExists($id_covoit)) {
                throw new Exception("Covoiturage with ID $id_covoit does not exist.");
            }

            // Prepare SQL query
            $sql = "INSERT INTO colis (
                        id_client, id_covoit, statut, date_colis, 
                        longueur, largeur, hauteur, poids, 
                        latitude_ram, longitude_ram, 
                        latitude_dest, longitude_dest, prix
                    ) VALUES (
                        :id_client, :id_covoit, :statut, :date_colis, 
                        :longueur, :largeur, :hauteur, :poids, 
                        :latitude_ram, :longitude_ram, 
                        :latitude_dest, :longitude_dest, :prix
                    )";

            $query = $db->prepare($sql);
            $query->bindValue(':id_client', $id_client);
            $query->bindValue(':id_covoit', $id_covoit, PDO::PARAM_NULL); // Allow null for id_covoit if necessary
            $query->bindValue(':statut', $statut);
            $query->bindValue(':date_colis', $date_colis);
            $query->bindValue(':longueur', $longueur);
            $query->bindValue(':largeur', $largeur);
            $query->bindValue(':hauteur', $hauteur);
            $query->bindValue(':poids', $poids);
            $query->bindValue(':latitude_ram', $latitude_ram);
            $query->bindValue(':longitude_ram', $longitude_ram);
            $query->bindValue(':latitude_dest', $latitude_dest);
            $query->bindValue(':longitude_dest', $longitude_dest);
            $query->bindValue(':prix', $prix);

            $query->execute();

        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Update an existing colis
    public function updateColis($id_colis, $id_client, $id_covoit, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $latitude_ram, $longitude_ram, $latitude_dest, $longitude_dest, $prix)
{
    $db = config::getConnexion();

    try {
        // Validate foreign key for client
        if (!$this->clientExists($id_client)) {
            throw new Exception("Client with ID $id_client does not exist.");
        }

        // Validate covoiturage existence if id_covoit is not NULL
        if ($id_covoit !== null && !$this->covoiturageExists($id_covoit)) {
            throw new Exception("Covoiturage with ID $id_covoit does not exist.");
        }

        // Prepare SQL query with the possibility of id_covoit being NULL
        $sql = "UPDATE colis SET 
                id_client = :id_client,
                id_covoit = :id_covoit,
                statut = :statut,
                date_colis = :date_colis,
                longueur = :longueur,
                largeur = :largeur,
                hauteur = :hauteur,
                poids = :poids,
                latitude_ram = :latitude_ram,
                longitude_ram = :longitude_ram,
                latitude_dest = :latitude_dest,
                longitude_dest = :longitude_dest,
                prix = :prix
                WHERE id_colis = :id_colis";

        $query = $db->prepare($sql);
        $query->bindValue(':id_colis', $id_colis);
        $query->bindValue(':id_client', $id_client);

        // Bind id_covoit properly, check if it's NULL and use PDO::PARAM_NULL for it
        if ($id_covoit === null) {
            $query->bindValue(':id_covoit', null, PDO::PARAM_NULL);  // This ensures NULL is allowed
        } else {
            $query->bindValue(':id_covoit', $id_covoit, PDO::PARAM_INT);  // Regular binding for non-NULL value
        }

        $query->bindValue(':statut', $statut);
        $query->bindValue(':date_colis', $date_colis);
        $query->bindValue(':longueur', $longueur);
        $query->bindValue(':largeur', $largeur);
        $query->bindValue(':hauteur', $hauteur);
        $query->bindValue(':poids', $poids);
        $query->bindValue(':latitude_ram', $latitude_ram);
        $query->bindValue(':longitude_ram', $longitude_ram);
        $query->bindValue(':latitude_dest', $latitude_dest);
        $query->bindValue(':longitude_dest', $longitude_dest);
        $query->bindValue(':prix', $prix);

        $query->execute();
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

    // Check if the client exists
    public function clientExists($id_client)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE id_user = :id_client"; // Replace users if needed
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_client', $id_client);
            $query->execute();
            return $query->fetchColumn() > 0;
        } catch (Exception $e) {
            die('Error checking client: ' . $e->getMessage());
        }
    }

    // Check if the covoiturage exists
    public function covoiturageExists($id_covoit)
    {
        $sql = "SELECT COUNT(*) FROM covoiturage WHERE id_covoit = :id_covoit";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_covoit', $id_covoit);
            $query->execute();
            return $query->fetchColumn() > 0;
        } catch (Exception $e) {
            die('Error checking covoiturage: ' . $e->getMessage());
        }
    }
}
?>
