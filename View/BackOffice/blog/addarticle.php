<?php
require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
require_once __DIR__ . '/../../../Model/BackOffice/Article.php';
require '../../../vendor/autoload.php'; // Assure-toi que le fichier autoload de Composer est inclus

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
        $uploadDir = '../../../uploads/';
        move_uploaded_file($photoTmpPath, $uploadDir . $photoName);
    }

    // Créer l'article
    $article = new Article($titre, $contenu, $date_publication, $photoName, $auteur, $categorie, $tags);
    $articleC = new ArticleC();
    $articleC->addarticle($article);

    // Récupérer le titre de l'article
    $titre_article = $article->getTitre();

    // Connexion à la base de données pour récupérer les emails des abonnés (id_user hardcodés)
    require_once __DIR__ . '/../../../config.php'; // Chemin relatif
    $pdo = config::getConnexion();  // Récupérer l'instance de PDO via la méthode statique

    // Récupérer les emails des utilisateurs abonnés (hardcodés par id_user)
    $stmt = $pdo->prepare("SELECT email FROM user WHERE is_subscribed = 1 AND email IS NOT NULL");
    $stmt->execute();
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Serveur SMTP de Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emna.garbaa@gmail.com';  // Ton adresse Gmail
        $mail->Password   = 'gthf zqyi zzqq uyfr';    // Le mot de passe d'application généré
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587; // Port SMTP de Gmail

        // Paramètres de l'email
        $mail->setFrom('no-reply@transitx.com', 'TransitX');  // Adresse "from" de l'email
        $mail->isHTML(true);  // Utilisation de HTML pour le corps de l'email

        // Ajouter un sujet dynamique
        $mail->Subject = '🚀 Nouvel article disponible sur TransitX !';

        // Corps de l'email avec mise en forme HTML
        $mail->Body = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    margin: 0;
                    padding: 20px;
                }
                .container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                }
                .header {
                    background-color: #007bff;
                    color: #ffffff;
                    padding: 10px;
                    border-radius: 8px 8px 0 0;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 20px;
                    font-size: 16px;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #888;
                    margin-top: 20px;
                }
                .button {
                    background-color: #007bff;
                    color: #ffffff;
                    padding: 12px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    display: inline-block;
                    margin-top: 20px;
                }
                .button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🚀 Nouvel article disponible sur TransitX !</h1>
                </div>
                <div class="content">
                    <p>Bonjour,</p>
                    <p>Un nouvel article vient d\'être publié sur TransitX !</p>
                    <p><strong>Titre de l\'article : </strong>' . $titre_article . '</p>
                    <p>Nous vous invitons à le lire en vous connectant sur notre site.</p>
                </div>
                <div class="footer">
                    <p>Merci de faire partie de la communauté TransitX.</p>
                    <p><small>Si vous ne souhaitez plus recevoir de notifications, vous pouvez vous désabonner ici.</small></p>
                </div>
            </div>
        </body>
        </html>';

        // Envoi de l'email aux utilisateurs abonnés
        foreach ($emails as $email) {
            $mail->addAddress($email);  // Ajouter l'email de chaque abonné
            $mail->send();
            // Réinitialiser les adresses
            $mail->clearAddresses();
        }

        // Redirection après l'ajout de l'article
        header("Location: crud.php");
        exit;

    } catch (Exception $e) {
        echo "L'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
    }
}
?>

<html>
<!-- Code HTML de ton formulaire -->
</html>
