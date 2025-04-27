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
    public function listarticleSortedByDate($order = 'ASC') {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $sql = "SELECT * FROM article ORDER BY date_publication $order";
    
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur : ' . $e->getMessage());
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
                    photo = :photo,
                    auteur = :auteur
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
                ':auteur' => $offre->getAuteur(),


            ]);
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
    
    public function addarticle($article) {
        $sql = "INSERT INTO article (titre, contenu, date_publication, photo, auteur) 
                VALUES (:titre, :contenu, :date_publication, :photo, :auteur)";
    
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
            $query->bindValue(':contenu', $article->getContenu(), PDO::PARAM_STR);
            $query->bindValue(':date_publication', $article->getDatepublication(), PDO::PARAM_STR);
            $query->bindValue(':photo', $article->getPhoto(), PDO::PARAM_STR);
            $query->bindValue(':auteur', $article->getAuteur(), PDO::PARAM_STR);
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
        $sql = "SELECT * FROM article WHERE titre LIKE :searchTerm";
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->execute([':searchTerm' => "%" . $searchTerm . "%"]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
    public function getNombreCommentairesParArticle($id_article) {
        $db = config::getConnexion();
        $sql = "SELECT COUNT(*) as total FROM commentaire WHERE id_article = :id";
        $query = $db->prepare($sql);
        $query->execute([':id' => $id_article]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    public function getMostCommentedArticles() {
        $sql = "SELECT a.id_article, a.titre, COUNT(c.id_commentaire) AS nb_commentaires
                FROM article a
                LEFT JOIN commentaire c ON a.id_article = c.id_article
                GROUP BY a.id_article
                ORDER BY nb_commentaires DESC
                LIMIT 5";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);  // Assurez-vous de spécifier le format des résultats.
        } catch (PDOException $e) {
            // Gestion de l'exception : affichage de l'erreur ou retour d'un message spécifique
            error_log($e->getMessage());  // Optionnel : log l'erreur pour un debug futur
            return [];  // Retourne un tableau vide en cas d'erreur pour éviter un plantage de l'application
        }
    }
    
    
}
?>
