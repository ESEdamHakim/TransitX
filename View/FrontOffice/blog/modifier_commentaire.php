<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire = ?");
$stmt->execute([$id]);
$commentaire = $stmt->fetch();

if (!$commentaire) {
    echo "Commentaire introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier le Commentaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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
        .form-container {
            width: 60%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            font-size: 28px;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 16px;
            color: #555;
            display: block;
            margin-bottom: 10px;
        }
        .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
            background-color: #f9f9f9;
            color: #333;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .form-group button:hover {
            background-color: #388e3c;
        }
        footer {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">TransitX</div>
</header>

<div class="form-container">
    <h2>Modifier le Commentaire</h2>
    <form method="POST" action="/TransitX-main/Controller/FrontOffice/traiter_modif_commentaire.php">
        <input type="hidden" name="id_commentaire" value="<?php echo $commentaire['id_commentaire']; ?>">
        <input type="hidden" name="id_article" value="<?php echo $commentaire['id_article']; ?>">

        <div class="form-group">
            <label for="contenu_commentaire">Votre commentaire :</label>
            <textarea name="contenu_commentaire" id="contenu_commentaire" rows="6" required><?php echo htmlspecialchars($commentaire['contenu_commentaire']); ?></textarea>
        </div>

        <div class="form-group">
            <button type="submit">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<footer>
    <p>&copy; 2023 TransitX. Tous droits réservés.</p>
</footer>

</body>
</html>
