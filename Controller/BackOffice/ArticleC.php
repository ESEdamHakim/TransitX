<?php
require_once __DIR__ . '/../../config.php';

class ArticleC {
    public function listoffre() {
        $sql = "SELECT * FROM article";
        $db = config::getConnexion(); 
        try {
            $liste = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC); 
            return $liste; 
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage()); 
        }
    }
    public function deleteOffer($id_article) {
        $sql = "DELETE FROM article WHERE id_article = :id";
        $db = config::getConnexion(); 

        try {
            $query = $db->prepare($sql);
            $query->execute([
                ':id' => $id_article
            ]);
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }

    public function updateOffre($offre) {
        // Vérification que l'objet possède la méthode getId() avant de procéder
        if (!method_exists($offre, 'getIdarticle')) {
            throw new Exception('Erreur : la méthode getId() est introuvable dans l’objet passé.');
        }
    
        // Mise à jour de tous les champs
        $sql = "UPDATE article SET 
                    titre = :titre, 
                    contenu = :contenu, 
                    date_publication = :date_publication
                WHERE id_article = :id";
    
        $db = config::getConnexion(); 
    
        try {
            // Préparation et exécution de la requête avec les paramètres
            $query = $db->prepare($sql);
            $query->execute([
                ':id' => $offre->getIdarticle(),  // Utilisation de la méthode getId() pour obtenir l'ID
                ':titre' => $offre->getTitre(),
                ':contenu' => $offre->getContenu(),
                ':date_publication' => $offre->getDatepublication(),
            ]);
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
    
    // Ajout d'une offre
    public function addOffre($titre, $contenu, $date_publication) {
        $sql = "INSERT INTO article (titre, contenu, date_publication) 
                VALUES (:titre, :contenu, :date_publication)";
    
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':titre', $titre, PDO::PARAM_STR);
            $query->bindParam(':contenu', $contenu, PDO::PARAM_STR);
            $query->bindParam(':date_publication', $date_publication, PDO::PARAM_STR);
            $query->execute();
    
            return "Offre ajoutée avec succès."; 
        } catch (PDOException $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }

    // Récupérer une offre par son ID
    public function getOfferById($id_article) {
        $sql = "SELECT * FROM article WHERE id_article = :id";
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->execute([':id' => $id_article]);
            $offer = $query->fetch();  
            if ($offer) {
                return $offer;
            } else {
                return null;
            }
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }


    // Récupérer toutes les offres
    public function getAllOffres() {
        return $this->listoffre();
    }
}
?>
