<?php
require_once __DIR__ . '/../../../Controller/ArticleC.php';
require_once __DIR__ . '/../../../Model/Article.php';
require_once __DIR__ . '/../../assets/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $date_publication = $_POST['date_publication'];
    $auteur = $_POST['auteur'];
    $categorie = $_POST['categorie'];
    $tags = $_POST['tags'];

    $photoName = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = '../../assets/uploads/';
        move_uploaded_file($photoTmpPath, $uploadDir . $photoName);
    }

    // Créer l'article
    $article = new Article($titre, $contenu, $date_publication, $photoName, $auteur, $categorie, $tags);
    $articleC = new ArticleC();
    $articleC->addarticle($article);

    $titre_article = $article->getTitre();

    require_once __DIR__ . '/../../../config.php';
    $pdo = config::getConnexion();

    // Récupérer tous les emails des clients
    $stmt = $pdo->prepare("SELECT email FROM user WHERE type = 'client' AND email IS NOT NULL");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Paramètres généraux de l'email
    $subject = '🚀 Nouvel article disponible sur TransitX !';
    $body = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; }
                .container { max-width: 600px; margin: auto; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 10px; border-radius: 8px 8px 0 0; text-align: center; }
                .content { padding: 20px; font-size: 16px; }
                .footer { text-align: center; font-size: 12px; color: #888; margin-top: 20px; }
                .button { background-color: #007bff; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header"><h1>🚀 Nouvel article disponible sur TransitX !</h1></div>
                <div class="content">
                    <p>Bonjour,</p>
                    <p>Un nouvel article vient d\'être publié sur TransitX !</p>
                    <p><strong>Titre de l\'article :</strong> ' . htmlspecialchars($titre_article) . '</p>
                    <p>Nous vous invitons à le lire en vous connectant sur notre site.</p>
                </div>
                <div class="footer">
                    <p>Merci de faire partie de la communauté TransitX.</p>
                    <p><small>Si vous ne souhaitez plus recevoir de notifications, vous pouvez vous désabonner ici.</small></p>
                </div>
            </div>
        </body>
        </html>';

    // Envoyer l'email à chaque client
    foreach ($clients as $client) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'emna.garbaa@gmail.com';
            $mail->Password = 'gthf zqyi zzqq uyfr'; // mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@transitx.com', 'TransitX');
            $mail->addAddress($client['email']);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi à {$client['email']}: {$mail->ErrorInfo}");
        }
    }

    // Redirection après succès
    header("Location: crud.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TransitX - Gestion du Blog</title>
  <!-- Styles -->
  <link rel="stylesheet" href="../../assets/css/main.css" />
  <link rel="stylesheet" href="assets/css/crud.css" />
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />

    <style>
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            margin-top: 0.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-actions {
            margin-top: 1.5rem;
        }

        .form-group select {
            text-align: left;
            direction: ltr;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <section class="bus-form-section">
                <div class="container">
                    <div class="section-header">
                        <h2>Ajouter un Article
                            <p>Remplissez le formulaire ci-dessous pour enregistrer un nouveau blog.</p>
                        </h2>
                    </div>

                    <div class="bus-form-container">
                        <form class="bus-form" id="articleForm" method="POST">
                            <div class="form-grid">
                                <!-- Left Column -->
                                <div class="form-column">
                                    <div class="form-group">
                                        <label for="titre">Titre :</label>
                                        <input type="text" id="titre" name="titre" class="form-input" />
                                        <span class="error-message" id="titre-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="contenu">Contenu :</label>
                                        <textarea id="contenu" name="contenu" class="form-textarea" rows="4"></textarea>
                                        <span class="error-message" id="contenu-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="date_publication">Date de Publication :</label>
                                        <input type="date" id="date_publication" name="date_publication"
                                            class="form-input" />
                                        <span class="error-message" id="date-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="categorie">Catégorie :</label>
                                        <select id="categorie" name="categorie" class="form-input" required>
                                            <option value="">-- Sélectionnez une catégorie --</option>
                                            <option value="Conseils de voyage">Conseils de voyage</option>
                                            <option value="Sécurité">Sécurité</option>
                                            <option value="Économie et écologie">Économie et écologie</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                        <span class="error-message" id="categorie-error"></span>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="form-column">
                                    <div class="form-group">
                                        <label for="auteur">Auteur :</label>
                                        <input type="text" id="auteur" name="auteur" class="form-input" />
                                        <span class="error-message" id="auteur-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="tags">Tags (séparés par des virgules) :</label>
                                        <input type="text" id="tags" name="tags" class="form-input"
                                            placeholder="Exemple : Mobilité, Innovation" />
                                        <span class="error-message" id="tags-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="photo">Photo :</label>
                                        <input type="file" id="photo" name="photo" accept="image/*"
                                            class="form-input" />
                                        <span class="error-message" id="photo-error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions text-center">
                                <a href="crud.php" class="btn cancel" style="margin-left: 10px;">
                                    Annuler <i class="fas fa-times"></i>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Ajouter l'Article <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
        <script src="main.js"></script>
</body>

</html>