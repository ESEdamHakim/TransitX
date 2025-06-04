<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('../../../config.php');

$pdo = config::getConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_commentaire = isset($_POST['id_commentaire']) ? intval($_POST['id_commentaire']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    $id_user = $_SESSION['user_id'] ?? 0;

    if ($id_user === 0) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
        exit;
    }

    if ($id_commentaire > 0 && in_array($action, ['like', 'dislike'])) {
        try {
            // Vérifier si l'utilisateur a déjà voté sur ce commentaire
            $stmt = $pdo->prepare("SELECT vote FROM commentaire_vote WHERE id_user = :user AND id_commentaire = :commentaire");
            $stmt->execute(['user' => $id_user, 'commentaire' => $id_commentaire]);
            $vote = $stmt->fetchColumn();

            if ($vote === false) {
                // Pas de vote précédent : insérer nouveau vote
                $stmt = $pdo->prepare("INSERT INTO commentaire_vote (id_user, id_commentaire, vote) VALUES (:user, :commentaire, :vote)");
                $stmt->execute(['user' => $id_user, 'commentaire' => $id_commentaire, 'vote' => $action]);

                // Mettre à jour compteur
                if ($action === 'like') {
                    $stmt = $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes + 1 WHERE id_commentaire = :id");
                } else {
                    $stmt = $pdo->prepare("UPDATE commentaire SET nb_dislikes = nb_dislikes + 1 WHERE id_commentaire = :id");
                }
                $stmt->execute(['id' => $id_commentaire]);
            } else if ($vote === $action) {
                // Même vote, on supprime le vote (toggle off)
                $stmt = $pdo->prepare("DELETE FROM commentaire_vote WHERE id_user = :user AND id_commentaire = :commentaire");
                $stmt->execute(['user' => $id_user, 'commentaire' => $id_commentaire]);

                if ($action === 'like') {
                    $stmt = $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes - 1 WHERE id_commentaire = :id");
                } else {
                    $stmt = $pdo->prepare("UPDATE commentaire SET nb_dislikes = nb_dislikes - 1 WHERE id_commentaire = :id");
                }
                $stmt->execute(['id' => $id_commentaire]);
            } else {
                // Vote différent : changer le vote
                $stmt = $pdo->prepare("UPDATE commentaire_vote SET vote = :vote WHERE id_user = :user AND id_commentaire = :commentaire");
                $stmt->execute(['vote' => $action, 'user' => $id_user, 'commentaire' => $id_commentaire]);

                // Mettre à jour les compteurs en conséquence
                if ($action === 'like') {
                    // enlever dislike + ajouter like
                    $stmt = $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes + 1, nb_dislikes = nb_dislikes - 1 WHERE id_commentaire = :id");
                } else {
                    // enlever like + ajouter dislike
                    $stmt = $pdo->prepare("UPDATE commentaire SET nb_likes = nb_likes - 1, nb_dislikes = nb_dislikes + 1 WHERE id_commentaire = :id");
                }
                $stmt->execute(['id' => $id_commentaire]);
            }

            echo json_encode(['success' => true]);
            exit;

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}
