<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/TransitX-main/fpdf186/fpdf.php');

$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");
// Simulation d'un utilisateur connecté
$isLoggedIn = true; // Passe à false pour tester le cas "déconnecté"
$connectedUserId = 1; // Doit exister dans la table `user`
$userCheckStmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE id_user = ?");
$userCheckStmt->execute([$connectedUserId]);
$userExists = $userCheckStmt->fetchColumn() > 0;

$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo "<p>Article not found!</p>";
    exit;
}

$commentStmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_article = ? AND id_parent IS NULL ORDER BY date_commentaire DESC");
$commentStmt->execute([$article_id]);
$commentaires = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
function getReplies($pdo, $idCommentaire) {
    $stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_parent = ? ORDER BY date_commentaire ASC");
    $stmt->execute([$idCommentaire]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($article['titre']); ?> - TransitX</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #4CAF50;
            padding: 20px;
            text-align: center;
            color: white;
        }
        header .logo {
            font-size: 32px;
            font-weight: bold;
        }
        .blog-detail {
            width: 70%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .blog-detail h2 {
            font-size: 36px;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }
        .post-info {
            font-size: 16px;
            color: #888;
            text-align: center;
            margin-bottom: 40px;
        }
        .post-info small {
            color: #555;
        }
        .content {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
        }
        .content h3 {
            color: #4CAF50;
            font-size: 28px;
            margin-top: 40px;
        }
        .content p {
            margin-bottom: 25px;
        }
        footer {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
        .comment-section {
            margin: 40px auto;
            width: 70%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .comment-section h3 {
            font-size: 24px;
            color: #1f4f65;
            margin-bottom: 15px;
        }
        .comment-section form textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .comment-section form button {
            background-color: #97c3a2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
        }
        .comment-section form button:hover {
            background-color: #1f4f65;
            color: white;
        }
        .comments {
            margin-top: 40px;
        }
        .comment {
            background-color: #f1f1f1;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
        }
        .comment p {
            margin: 5px 0;
        }
        /* Style for social sharing buttons */
        .social-share {
            text-align: center;
            margin-top: 30px;
        }
        .social-share a {
            text-decoration: none;
            margin: 0 15px;
            font-size: 30px;
            color: #333;
            transition: color 0.3s ease;
        }
        .social-share a:hover {
            color: #4CAF50;
        }
        .social-share a i.fa-file-pdf:hover {
    color: #9a0007;
}

    </style>
</head>
<body>

<header>
    <div class="logo">TransitX</div>
</header>

<div class="blog-detail">
    <h2><?php echo htmlspecialchars($article['titre']); ?></h2>
    <div class="post-info">
        <p>
            <small><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($article['date_publication']); ?></small>
            <small><i class="fa fa-user"></i> <?php echo htmlspecialchars($article['auteur']); ?></small>
        </p>
    </div>

    <div class="content">
        <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>

        <h3>Les Options de Trajets Flexibles</h3>
        <p>Les trajets flexibles permettent de choisir une option qui s'adapte à votre emploi du temps et à vos besoins...</p>

        <h3>Les Bénéfices Écologiques et Économiques</h3>
        <p>En réduisant le nombre de voitures sur les routes, TransitX contribue à diminuer l'empreinte carbone...</p>

        <h3>Des Trajets Plus Efficaces Grâce à la Technologie</h3>
        <p>Grâce à l'intelligence artificielle et aux données de trajets, TransitX optimise les itinéraires...</p>
    </div>

<!-- Lien PDF dans le même style -->
<div class="social-share">
    <p>Obtenez une version PDF de l'article :</p>

    <!-- Lien PDF -->
    <a href="generate_pdf.php?id=<?php echo $article_id; ?>" target="_blank" title="Télécharger le PDF de l'article">
        <i class="fas fa-file-pdf" style="color: #D32F2F;"></i>
    </a>
</div>



</div>

<div class="comment-section">
    <div class="comments">
        <h3>Commentaires</h3>
        <?php if (count($commentaires) === 0): ?>
            <p>Pas encore de commentaires.</p>
        <?php else: ?>
            <?php foreach ($commentaires as $commentaire): ?>
                <div class="comment" style="display: flex; align-items: flex-start; margin-bottom: 15px; justify-content: space-between;">
                    <!-- Photo de la personne qui a commenté -->
                    <div class="commenter-photo" style="flex-shrink: 0; margin-right: 15px;">
                        <img src="/TransitX-main/uploads/portrait.avif" alt="Photo de l'utilisateur" style="width:50px; height:50px; border-radius: 50%;">
                    </div>

                    <!-- Contenu du commentaire avec la date en dessous -->
                    <div class="comment-content" style="flex-grow: 1;">
                        <p><?php echo nl2br(htmlspecialchars($commentaire['contenu_commentaire'])); ?></p>
                        <div class="comment-date" style="font-size: 0.9em; color: #888; margin-top: 5px;">
                            <?php echo htmlspecialchars($commentaire['date_commentaire']); ?>
                        </div>

                        <!-- Like / Dislike -->
                        <div class="comment-likes" style="margin-top: 8px;">
                            <span>❤️ <?php echo $commentaire['nb_likes']; ?></span>
                            <a href="like_dislike.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=like" style="margin-right: 10px; color: green;">Like</a>

                            <span>👎 <?php echo $commentaire['nb_dislikes']; ?></span>
                            <a href="like_dislike.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=dislike" style="color: red;">Dislike</a>
                        </div>
                    </div> 

                    <!-- Bouton pour répondre -->
                    <form action="process_comment.php" method="POST" style="margin-top: 10px;">
                        <input type="hidden" name="id_article" value="<?php echo $article_id; ?>">
                        <input type="hidden" name="id_user" value="<?php echo $connectedUserId; ?>">
                        <input type="hidden" name="id_parent" value="<?php echo $commentaire['id_commentaire']; ?>"> <!-- ID du commentaire parent -->
                        <textarea name="comment" rows="2" placeholder="Répondre à ce commentaire..." required></textarea>
                        <br />
                        <button type="submit" style="margin-top: 5px;">Répondre</button>
                    </form>

                    <!-- Boutons Modifier et Supprimer (icônes avec la couleur de l'interface) -->
                    <div class="comment-actions" style="display: flex; align-items: center; gap: 10px;">
                        <!-- Bouton Modifier (icône en vert) -->
                        <form method="get" action="modifier_commentaire.php" style="margin: 0;">
                            <input type="hidden" name="id" value="<?php echo $commentaire['id_commentaire']; ?>">
                            <button type="submit" style="background: none; border: none; color: #4CAF50; font-size: 20px; cursor: pointer;">
                                <i class="fas fa-edit"></i> <!-- Icône Modifier -->
                            </button>
                        </form>

                        <!-- Bouton Supprimer (icône en rouge) -->
                        <a href="/TransitX-main/View/FrontOffice/blog/supprimer_commentaire.php?id_commentaire=<?php echo $commentaire['id_commentaire']; ?>&id_article=<?php echo $article['id_article']; ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');"
                           style="color: red; font-size: 20px; text-decoration: none;">
                            <i class="fas fa-trash-alt"></i> <!-- Icône Supprimer -->
                        </a>
                    </div>
                </div>

                <!-- Affichage des réponses sous le commentaire -->
                <?php
                    $replies = getReplies($pdo, $commentaire['id_commentaire']);
                    foreach ($replies as $reply): 
                ?>
                    <div class="comment" style="margin-left: 50px; background: #e9ecef;">
                        <div class="comment-content">
                            <p><?php echo nl2br(htmlspecialchars($reply['contenu_commentaire'])); ?></p>
                            <div class="comment-date" style="font-size: 0.8em; color: #666; margin-top: 5px;">
                                <?php echo htmlspecialchars($reply['date_commentaire']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endforeach; ?>





        <?php endif; ?>
    </div>

    <h3>Ajouter un commentaire</h3>
    <?php if ($isLoggedIn && $userExists): ?>
        <form action="process_comment.php" method="POST">
    <input type="hidden" name="id_article" value="<?php echo $article_id; ?>">
    <input type="hidden" name="id_user" value="<?php echo $connectedUserId; ?>">
    <input type="hidden" name="id_parent" value="NULL"> <!-- commentaire de base (pas une réponse) -->
    <textarea name="comment" id="comment" rows="4" placeholder="Écrivez votre commentaire ici..." required></textarea>
    <br />
    <button type="submit">Commenter</button>
</form>

<?php elseif ($isLoggedIn && !$userExists): ?>
    <p style="color: red;">Vous devez être connecté pour laisser un commentaire.</p>
<?php else: ?>
    <p style="color: red;">Vous devez être connecté pour laisser un commentaire.</p>
<?php endif; ?>


</div>

<footer>
<p>&copy; 2023 TransitX. Tous droits réservés.</p>
</footer>

</body>
</html> 