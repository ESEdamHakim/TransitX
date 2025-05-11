<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/vehicule.php';

class VehiculeC
{
    // List all vehicles
    public function listVehicules()
    {
        $sql = "SELECT * FROM vehicule";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new vehicle
    public function addVehicule(Vehicule $vehicule)
    {
        $sql = "INSERT INTO vehicule 
                (matricule, type_vehicule, nb_places, couleur, marque, modele, confort, photo_vehicule, id_user) 
                VALUES 
                (:matricule, :type_vehicule, :nb_places, :couleur, :marque, :modele, :confort, :photo_vehicule, :id_user)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                ':matricule' => $vehicule->getMatricule(),
                ':type_vehicule' => $vehicule->getTypeVehicule(),
                ':nb_places' => $vehicule->getNbPlaces(),
                ':couleur' => $vehicule->getCouleur(),
                ':marque' => $vehicule->getMarque(),
                ':modele' => $vehicule->getModele(),
                ':confort' => $vehicule->getConfort(),
                ':photo_vehicule' => $vehicule->getPhotoVehicule(),
                ':id_user' => $vehicule->getIdUser()
            ]);
            return "Véhicule ajouté avec succès.";
        } catch (Exception $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }

    // Delete a vehicle by ID (with user verification for FrontOffice)
    public function deleteVehicule($id_vehicule, $id_user = null, $isAdmin = false)
    {
        $db = config::getConnexion();

        try {
            if ($isAdmin) {
                // Admin can delete any vehicle
                $sql = "DELETE FROM vehicule WHERE id_vehicule = :id";
                $query = $db->prepare($sql);
                $query->execute([':id' => $id_vehicule]);
            } else {
                // Regular user can only delete their own vehicle
                $sql = "DELETE FROM vehicule WHERE id_vehicule = :id AND id_user = :id_user";
                $query = $db->prepare($sql);
                $query->execute([
                    ':id' => $id_vehicule,
                    ':id_user' => $id_user
                ]);
            }
            return "Véhicule supprimé avec succès.";
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // Update an existing vehicle (with user verification for FrontOffice)
    public function updateVehicule(Vehicule $vehicule, $id_user = null, $isAdmin = false)
    {
        $db = config::getConnexion();

        try {
            if ($isAdmin) {
                // Admin can update any vehicle (BackOffice)
                $sql = "UPDATE vehicule SET 
                            matricule = :matricule,
                            type_vehicule = :type_vehicule,
                            nb_places = :nb_places,
                            couleur = :couleur,
                            marque = :marque,
                            modele = :modele,
                            confort = :confort,
                            photo_vehicule = :photo_vehicule
                        WHERE id_vehicule = :id";
                $query = $db->prepare($sql);
                $query->execute([
                    ':id' => $vehicule->getIdVehicule(),
                    ':matricule' => $vehicule->getMatricule(),
                    ':type_vehicule' => $vehicule->getTypeVehicule(),
                    ':nb_places' => $vehicule->getNbPlaces(),
                    ':couleur' => $vehicule->getCouleur(),
                    ':marque' => $vehicule->getMarque(),
                    ':modele' => $vehicule->getModele(),
                    ':confort' => $vehicule->getConfort(),
                    ':photo_vehicule' => $vehicule->getPhotoVehicule()
                ]);
            } else {
                // Regular user can only update their own vehicle (FrontOffice)
                $sql = "UPDATE vehicule SET 
                            matricule = :matricule,
                            type_vehicule = :type_vehicule,
                            nb_places = :nb_places,
                            couleur = :couleur,
                            marque = :marque,
                            modele = :modele,
                            confort = :confort,
                            photo_vehicule = :photo_vehicule
                        WHERE id_vehicule = :id AND id_user = :id_user";
                $query = $db->prepare($sql);
                $query->execute([
                    ':id' => $vehicule->getIdVehicule(),
                    ':matricule' => $vehicule->getMatricule(),
                    ':type_vehicule' => $vehicule->getTypeVehicule(),
                    ':nb_places' => $vehicule->getNbPlaces(),
                    ':couleur' => $vehicule->getCouleur(),
                    ':marque' => $vehicule->getMarque(),
                    ':modele' => $vehicule->getModele(),
                    ':confort' => $vehicule->getConfort(),
                    ':photo_vehicule' => $vehicule->getPhotoVehicule(),
                    ':id_user' => $id_user
                ]);
            }
            return "Véhicule mis à jour avec succès.";
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // Get a single vehicle by ID
    public function getVehiculeById($id_vehicule)
    {
        $sql = "SELECT * FROM vehicule WHERE id_vehicule = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([':id' => $id_vehicule]);
            $vehicule = $query->fetch();
            return $vehicule ?: null;
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // List all vehicles for a specific user
    public function listUserVehicules($id_user)
    {
        $sql = "SELECT * FROM vehicule WHERE id_user = :id_user";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([':id_user' => $id_user]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }
   
public function getVehiculeByMatriculeAndUser($matricule, $id_user)
{
    $sql = "SELECT * FROM vehicule WHERE matricule = :matricule AND id_user = :id_user";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute([
            ':matricule' => $matricule,
            ':id_user' => $id_user
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}

public function getVehiculesByUser($id_user)
{
    $sql = "SELECT id_vehicule, matricule FROM vehicule WHERE id_user = :id_user";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute([':id_user' => $id_user]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}

public function listVehicules2()
{
    $sql = "SELECT 
                vehicule.id_vehicule,
                vehicule.matricule,
                vehicule.type_vehicule,
                vehicule.nb_places,
                vehicule.couleur,
                vehicule.marque,
                vehicule.modele,
                vehicule.confort,
                vehicule.photo_vehicule,
                vehicule.id_user,
                user.nom AS user_name
            FROM vehicule
            LEFT JOIN user ON vehicule.id_user = user.id";

    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception('Erreur : ' . $e->getMessage());
    }
}
}
?>