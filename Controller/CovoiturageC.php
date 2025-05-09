<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../model/Covoiturage.php';

class CovoiturageC
{

    // List all covoiturages
    public function listCovoiturages()
    {
        $sql = "SELECT id_covoit, date_depart, lieu_depart, lieu_arrivee, accepte_colis, colis_complet, details, prix, temps_depart, places_dispo, id_user,id_vehicule
                FROM covoiturage";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new covoiturage
    public function addCovoiturage(Covoiturage $covoit)
    {
        $sql = "INSERT INTO covoiturage 
                (date_depart, lieu_depart, lieu_arrivee, accepte_colis, colis_complet, details, prix, temps_depart, places_dispo, id_user,id_vehicule) 
                VALUES 
                (:date_depart, :lieu_depart, :lieu_arrivee, :accepte_colis, :colis_complet, :details, :prix, :temps_depart, :places_dispo, :id_user,:id_vehicule)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                ':date_depart' => $covoit->getDateDepart(),
                ':lieu_depart' => $covoit->getLieuDepart(),
                ':lieu_arrivee' => $covoit->getLieuArrivee(),
                ':accepte_colis' => $covoit->getAccepteColis(),
                ':colis_complet' => $covoit->getColisComplet(),
                ':details' => $covoit->getDetails(),
                ':prix' => $covoit->getPrix(),
                ':temps_depart' => $covoit->getTempsDepart(),
                ':places_dispo' => $covoit->getPlacesDispo(),
                ':id_user' => $covoit->getIdUser(),
                ':id_vehicule' => $covoit->getIdVehicule()
            ]);
            return "Trajet ajouté avec succès.";
        } catch (Exception $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }


    // Delete a covoiturage by ID (with user verification for FrontOffice)
    public function deleteCovoiturage($id_covoit, $id_user = null, $isAdmin = false)
    {
        $db = config::getConnexion();

        try {
            if ($isAdmin) {
                // Admin can delete any covoiturage
                $sql = "DELETE FROM covoiturage WHERE id_covoit = :id";
                $query = $db->prepare($sql);
                $query->execute([':id' => $id_covoit]);
            } else {
                // Regular user can only delete their own covoiturage
                $sql = "DELETE FROM covoiturage WHERE id_covoit = :id AND id_user = :id_user";
                $query = $db->prepare($sql);
                $query->execute([
                    ':id' => $id_covoit,
                    ':id_user' => $id_user
                ]);
            }
            return "Trajet supprimé avec succès.";
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }
    // Update an existing covoiturage (with user verification for FrontOffice)
    public function updateCovoiturage(Covoiturage $covoit, $id_user = null, $isAdmin = false)
    {
        $db = config::getConnexion();

        try {
            if ($isAdmin) {
                // Admin can update any covoiturage (BackOffice)
                $sql = "UPDATE covoiturage SET 
                            date_depart = :date_depart,
                            lieu_depart = :lieu_depart,
                            lieu_arrivee = :lieu_arrivee,
                            accepte_colis = :accepte_colis,
                            colis_complet = :colis_complet,
                            details = :details,
                            prix = :prix,
                            temps_depart = :temps_depart,
                            places_dispo = :places_dispo
                        WHERE id_covoit = :id";
                $query = $db->prepare($sql);
                $query->execute([
                    ':id' => $covoit->getIdCovoit(),
                    ':date_depart' => $covoit->getDateDepart(),
                    ':lieu_depart' => $covoit->getLieuDepart(),
                    ':lieu_arrivee' => $covoit->getLieuArrivee(),
                    ':accepte_colis' => $covoit->getAccepteColis(),
                    ':colis_complet' => $covoit->getColisComplet(),
                    ':details' => $covoit->getDetails(),
                    ':prix' => $covoit->getPrix(),
                    ':temps_depart' => $covoit->getTempsDepart(),
                    ':places_dispo' => $covoit->getPlacesDispo()
                ]);
            } else {
                // Regular user can only update their own covoiturage (FrontOffice)
                $sql = "UPDATE covoiturage SET 
                            date_depart = :date_depart,
                            lieu_depart = :lieu_depart,
                            lieu_arrivee = :lieu_arrivee,
                            accepte_colis = :accepte_colis,
                            colis_complet = :colis_complet,
                            details = :details,
                            prix = :prix,
                            temps_depart = :temps_depart,
                            places_dispo = :places_dispo,
                            id_vehicule = :id_vehicule
                        WHERE id_covoit = :id AND id_user = :id_user";
                $query = $db->prepare($sql);
                $query->execute([
                    ':id' => $covoit->getIdCovoit(),
                    ':date_depart' => $covoit->getDateDepart(),
                    ':lieu_depart' => $covoit->getLieuDepart(),
                    ':lieu_arrivee' => $covoit->getLieuArrivee(),
                    ':accepte_colis' => $covoit->getAccepteColis(),
                    ':colis_complet' => $covoit->getColisComplet(),
                    ':details' => $covoit->getDetails(),
                    ':prix' => $covoit->getPrix(),
                    ':temps_depart' => $covoit->getTempsDepart(),
                    ':places_dispo' => $covoit->getPlacesDispo(),
                    ':id_vehicule' => $covoit->getIdVehicule(),
                    ':id_user' => $id_user // Ensure only the owner can update
                ]);
            }
            return "Trajet mis à jour avec succès.";
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }
    // Get a single covoiturage by ID
    public function getCovoiturageById($id_covoit)
    {
        $sql = "SELECT * FROM covoiturage WHERE id_covoit = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([':id' => $id_covoit]);
            $covoit = $query->fetch();
            return $covoit ?: null;
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // Get all covoiturages
    public function getAllCovoiturages()
    {
        return $this->listCovoiturages();
    }
    public function listUserCovoiturages($id_user)
    {
        $sql = "SELECT * FROM covoiturage WHERE id_user = :id_user";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([':id_user' => $id_user]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }
public function listUserCovoiturage()
{
    $sql = "SELECT 
                covoiturage.id_covoit,
                covoiturage.date_depart,
                covoiturage.lieu_depart,
                covoiturage.lieu_arrivee,
                covoiturage.accepte_colis,
                covoiturage.colis_complet,
                covoiturage.details,
                covoiturage.prix,
                covoiturage.temps_depart,
                covoiturage.places_dispo,
                covoiturage.id_user,
                user.nom AS user_name
            FROM covoiturage
            LEFT JOIN user ON covoiturage.id_user = user.id";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}
public function getVehiculeIdByCovoiturageId($id_covoiturage)
{
    $sql = "SELECT id_vehicule FROM covoiturage WHERE id_covoit = :id_covoiturage"; // Use id_covoit
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute([':id_covoiturage' => $id_covoiturage]);
        $result = $query->fetch();
        return $result['id_vehicule'] ?? null; // Return the id_vehicule or null if not found
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}


public function searchCovoiturages($departure, $destination, $date)
{
    $sql = "SELECT * FROM covoiturage WHERE 1=1";
    $params = [];

    if (!empty($departure)) {
        $sql .= " AND lieu_depart LIKE :departure";
        $params[':departure'] = '%' . $departure . '%';
    }

    if (!empty($destination)) {
        $sql .= " AND lieu_arrivee LIKE :destination";
        $params[':destination'] = '%' . $destination . '%';
    }

    if (!empty($date)) {
        $sql .= " AND date_depart = :date";
        $params[':date'] = $date;
    }

    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);


        $query->execute($params);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}
public function getUserById($id_user)
{
    $sql = "SELECT nom, prenom, email, telephone FROM user WHERE id = :id_user";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute([':id_user' => $id_user]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}


public function getBookingStatus($id_covoiturage, $id_user)
{
    $sql = "SELECT notification_status 
            FROM bookings 
            WHERE id_covoiturage = :id_covoiturage AND id_user = :id_user";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_user' => $id_user
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['notification_status'] ?? null; // Return the status or null if no booking exists
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}
public function getBookingRequests($id_user) {
    $db = config::getConnexion();
    $sql = "SELECT b.id_covoiturage, b.id_user 
            FROM bookings b
            INNER JOIN covoiturage c ON b.id_covoiturage = c.id_covoit
            WHERE c.id_user = :id_user AND b.notification_status = 'pending'";
    $query = $db->prepare($sql);
    $query->execute([':id_user' => $id_user]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Format the results as an associative array
    $bookingRequests = [];
    foreach ($results as $row) {
        $bookingRequests[$row['id_covoiturage']] = $row;
    }

    return $bookingRequests;
}
}
?>