<?php
require '../../../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connexion à la base de données pour récupérer les emails des abonnés
require_once __DIR__ . '/../../../config.php'; // Chemin relatif
$pdo = config::getConnexion();  // Récupérer l'instance de PDO via la méthode statique

// Récupérer les emails des utilisateurs abonnés
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
    $mail->Body = 'Bonjour,<br><br>Un nouvel article vient d\'être publié sur TransitX !<br><br>Titre de l\'article : ' . $titre_article . '<br><br>Connectez-vous pour le lire.<br><br>À très vite !';

    // Envoi de l'email aux utilisateurs abonnés
    foreach ($emails as $email) {
        $mail->addAddress($email);  // Ajouter l'email de chaque abonné
        $mail->send();
        // Réinitialiser les adresses après chaque envoi
        $mail->clearAddresses();
    }

    echo 'L\'email a bien été envoyé à tous les abonnés !';

} catch (Exception $e) {
    echo "L'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
}
?>
