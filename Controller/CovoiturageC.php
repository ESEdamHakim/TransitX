<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../model/Covoiturage.php';

class CovoiturageC {

    // List all covoiturages
    public function listCovoiturages() {
        $sql = "SELECT id_covoit, date_depart, lieu_depart, lieu_arrivee, accepte_colis, colis_complet, details, prix, temps_depart, places_dispo
                FROM covoiturage";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new covoiturage
    public function addCovoiturage(Covoiturage $covoit) {
        $sql = "INSERT INTO covoiturage 
                (date_depart, lieu_depart, lieu_arrivee, accepte_colis, colis_complet, details, prix, temps_depart, places_dispo) 
                VALUES 
                (:date_depart, :lieu_depart, :lieu_arrivee, :accepte_colis, :colis_complet, :details, :prix, :temps_depart, :places_dispo)";

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
                ':places_dispo' => $covoit->getPlacesDispo()
            ]);
            return "Trajet ajouté avec succès.";
        } catch (Exception $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }

    // Delete a covoiturage by ID
    public function deleteCovoiturage($id_covoit) {
        $sql = "DELETE FROM covoiturage WHERE id_covoit = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([':id' => $id_covoit]);
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // Update an existing covoiturage
    public function updateCovoiturage(Covoiturage $covoit) {
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

        $db = config::getConnexion();

        try {
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
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
        }
    }

    // Get a single covoiturage by ID
    public function getCovoiturageById($id_covoit) {
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
    public function getAllCovoiturages() {
        return $this->listCovoiturages();
    }
}
?>