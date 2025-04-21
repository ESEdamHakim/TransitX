<?php
require_once __DIR__ . '/../../config.php';

class ArticleC {
    public function listarticle() {
        $sql = "SELECT * FROM article";
        $db = config::getConnexion(); 
        try {
            $liste = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC); 
            return $liste; 
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage()); 
        }
    }
    public function deletearticle($id_article) {
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

    public function updatearticle($offre) {
        if (!method_exists($offre, 'getIdarticle')) {
            throw new Exception('Erreur : la méthode getId() est introuvable dans l’objet passé.');
        }
    
        $sql = "UPDATE article SET 
                    titre = :titre, 
                    contenu = :contenu, 
                    date_publication = :date_publication,
                    photo = :photo
                WHERE id_article = :id";
    
        $db = config::getConnexion(); 
    
        try {
            $query = $db->prepare($sql);
            $query->execute([
                ':id' => $offre->getIdarticle(),  
                ':titre' => $offre->getTitre(),
                ':contenu' => $offre->getContenu(),
                ':date_publication' => $offre->getDatepublication(),
                ':photo' => $offre->getPhoto(),

            ]);
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
    
    public function addarticle($article) {
        $sql = "INSERT INTO article (titre, contenu, date_publication, photo) 
                VALUES (:titre, :contenu, :date_publication, :photo)";
    
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
            $query->bindValue(':contenu', $article->getContenu(), PDO::PARAM_STR);
            $query->bindValue(':date_publication', $article->getDatepublication(), PDO::PARAM_STR);
            $query->bindValue(':photo', $article->getPhoto(), PDO::PARAM_STR);
            $query->execute();
    
            return "Article ajouté avec succès."; 
        } catch (PDOException $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }
    

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
    public function afficherCommentaires($idArticle) {
        // Utiliser la connexion de la config (au lieu de créer une nouvelle connexion)
        $db = config::getConnexion();
        
        // Requête SQL pour récupérer les commentaires liés à l'article
        $sql = "SELECT * FROM commentaire WHERE id_article = :idArticle";
        $query = $db->prepare($sql);
        $query->bindParam(':idArticle', $idArticle, PDO::PARAM_INT);
        $query->execute();
        
        // Retourner les résultats (tous les commentaires de l'article)
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchArticles($searchTerm) {
        // Crée une connexion à la base de données
        $db = Database::getConnection();
        
        // Préparer la requête SQL pour rechercher les articles avec le terme donné
        $stmt = $db->prepare("SELECT * FROM articles WHERE titre LIKE :searchTerm OR contenu LIKE :searchTerm");
        $stmt->execute([':searchTerm' => "%$searchTerm%"]);
        
        // Retourne les articles trouvés
        return $stmt->fetchAll();
    }
    
}
?>
