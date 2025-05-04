<?php
$summary = "";
$error = "";

// Vérifier si l'ID de l'article est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $article_id = $_GET['id'];

    // Connexion à la base de données et récupération de l'article
    try {
        // Modifie ici les informations de connexion
        $pdo = new PDO('mysql:host=localhost;dbname=transitx', 'root', ''); // Utilisation de 'root' et mot de passe vide pour un serveur local
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = :id_article");
        $stmt->execute(['id_article' => $article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            $contenu = $article['contenu'];  // Contenu de l'article à résumer
        } else {
            $error = "Article non trouvé.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion à la base de données : " . $e->getMessage();
    }
} else {
    $error = "ID de l'article manquant.";
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["contenu"]) && isset($article_id)) {
    $contenu = $_POST["contenu"];

    // Appel API Hugging Face
    $api_url = "https://api-inference.huggingface.co/models/facebook/bart-large-cnn";
    $api_key = "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";

    $data = json_encode(["inputs" => $contenu]);

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = "Erreur cURL : " . curl_error($ch);
    } else {
        $result = json_decode($response, true);

        if (isset($result[0]["summary_text"])) {
            $summary = $result[0]["summary_text"];

            // Mise à jour du contenu de l'article dans la base de données
            try {
                $updateStmt = $pdo->prepare("UPDATE article SET contenu = :resume WHERE id_article = :id");
                $updateStmt->execute([
                    'resume' => $summary,
                    'id' => $article_id
                ]);
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour de l'article : " . $e->getMessage();
            }

        } else {
            $error = "Erreur lors de la génération du résumé : " . json_encode($result);
        }
    }

    curl_close($ch);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur de Résumé</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9fb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            max-width: 700px;
            width: 100%;
            background-color: #ffffff;
            margin: 40px 20px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            color: #34495e;
        }

        textarea {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            margin-top: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 0 auto;
        }

        button:hover {
            background-color: #2980b9;
        }

        .summary {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            color: #2c3e50;
            font-size: 16px;
            line-height: 1.6;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>📝 Générateur de Résumé d'Article</h1>

        <!-- Formulaire -->
        <form method="POST">
            <label for="contenu">Contenu de l’article :</label>
            <textarea id="contenu" name="contenu" rows="10" required placeholder="Colle ici ton article..."><?= htmlspecialchars($contenu ?? '') ?></textarea>
            <button type="submit">Générer le Résumé</button>
        </form>

        <!-- Résumé généré -->
        <?php if ($summary): ?>
            <h2>Résumé généré :</h2>
            <div class="summary"><?= htmlspecialchars($summary) ?></div>
        <?php elseif ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
