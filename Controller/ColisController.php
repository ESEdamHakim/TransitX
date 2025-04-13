<?php
// ColisController.php

include "config.php";

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
    public function addColis($id_client, $id_covoit, $adresse, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $latitude_dest, $longitude_dest, $prix) {
        $sql = "INSERT INTO colis (id_client, id_covoit, adresse, statut, date_colis, longueur, largeur, hauteur, poids, latitude_dest, longitude_dest, prix) 
                VALUES (:id_client, :id_covoit, :adresse, :statut, :date_colis, :longueur, :largeur, :hauteur, :poids, :latitude_dest, :longitude_dest, :prix)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_client', $id_client);
            $query->bindValue(':id_covoit', $id_covoit);
            $query->bindValue(':adresse', $adresse);
            $query->bindValue(':statut', $statut);
            $query->bindValue(':date_colis', $date_colis);
            $query->bindValue(':longueur', $longueur);
            $query->bindValue(':largeur', $largeur);
            $query->bindValue(':hauteur', $hauteur);
            $query->bindValue(':poids', $poids);
            $query->bindValue(':latitude_dest', $latitude_dest);
            $query->bindValue(':longitude_dest', $longitude_dest);
            $query->bindValue(':prix', $prix); // Bind the prix value
            $query->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Update an existing colis
    public function updateColis($id_colis, $id_client, $id_covoit, $adresse, $statut, $date_colis, $longueur, $largeur, $hauteur, $poids, $latitude_dest, $longitude_dest, $prix)
    {
        $sql = "UPDATE colis SET 
                id_client = :id_client,
                id_covoit = :id_covoit,
                adresse = :adresse,
                statut = :statut,
                date_colis = :date_colis,
                longueur = :longueur,
                largeur = :largeur,
                hauteur = :hauteur,
                poids = :poids,
                latitude_dest = :latitude_dest,
                longitude_dest = :longitude_dest,
                prix = :prix
                WHERE id_colis = :id_colis";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_colis', $id_colis);
            $query->bindValue(':id_client', $id_client);
            $query->bindValue(':id_covoit', $id_covoit);
            $query->bindValue(':adresse', $adresse);
            $query->bindValue(':statut', $statut);
            $query->bindValue(':date_colis', $date_colis);
            $query->bindValue(':longueur', $longueur);
            $query->bindValue(':largeur', $largeur);
            $query->bindValue(':hauteur', $hauteur);
            $query->bindValue(':poids', $poids);
            $query->bindValue(':latitude_dest', $latitude_dest);
            $query->bindValue(':longitude_dest', $longitude_dest);
            $query->bindValue(':prix', $prix); // Bind the prix value
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
            return $query->execute(); // Return true if successful
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>
