<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo "<p>Article not found!</p>";
    exit;
}

$commentStmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_article = ? ORDER BY date_commentaire DESC");
$commentStmt->execute([$article_id]);
$commentaires = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
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
    </style>
</head>
<body>

<header>
    <div class="logo">TransitX</div>
</header>

<div class="blog-detail">
    <h2><?php echo htmlspecialchars($article['titre']); ?></h2>
    <div class="post-info">
        <p><small><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($article['date_publication']); ?></small></p>
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
</div>

<!-- Section Affichage des Commentaires -->
<div class="comment-section">
    <div class="comments">
        <h3>Commentaires</h3>
        <?php if (count($commentaires) === 0): ?>
            <p>Pas encore de commentaires.</p>
        <?php else: ?>
            <?php foreach ($commentaires as $commentaire): ?>
                <div class="comment">
                    <p><?php echo nl2br(htmlspecialchars($commentaire['contenu_commentaire'])); ?></p>
                    <small>Posté le <?php echo htmlspecialchars($commentaire['date_commentaire']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <h3>Ajouter un commentaire</h3>
    <form action="process_comment.php" method="POST">
        <input type="hidden" name="id_article" value="<?php echo $article_id; ?>">
        <textarea name="comment" id="comment" rows="4" placeholder="Écrivez votre commentaire ici..." required></textarea>
        <br />
        <button type="submit">Commenter</button>
    </form>
</div>

<footer>
    <p>&copy; 2023 TransitX. Tous droits réservés.</p>
</footer>

</body>
</html>
