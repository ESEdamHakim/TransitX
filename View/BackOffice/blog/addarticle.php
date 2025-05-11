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
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'emna.garbaa@gmail.com';
            $mail->Password   = 'gthf zqyi zzqq uyfr'; // mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@transitx.com', 'TransitX');
            $mail->addAddress($client['email']);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

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

<html>
<!-- Ton formulaire HTML ici -->
</html>
