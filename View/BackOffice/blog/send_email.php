<?php
// Assurez-vous que vous incluez le fichier autoload de Composer pour utiliser PHPMailer
require '../../../vendor/autoload.php'; // Chemin vers autoload.php généré par Composer

// Utiliser les classes PHPMailer et Exception de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Créer une instance de PHPMailer
$mail = new PHPMailer(true);

try {
    // Paramètres du serveur SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';                    // Serveur SMTP de Gmail
    $mail->SMTPAuth   = true;
    $mail->Username   = 'emna.garbaa@gmail.com';  // Ton adresse Gmail
    $mail->Password   = 'gthf zqyi zzqq uyfr';    // Le mot de passe d'application généré
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;                                  // Port SMTP de Gmail

    // Destinataire
    $mail->setFrom('no-reply@transitx.com', 'TransitX');
    $mail->addAddress('feirouz.lajnef@gmail.com');            // Email du destinataire (peut être dynamique)

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = '🚀 Nouvel article disponible sur TransitX !';
    $mail->Body    = 'Bonjour,<br><br>Un nouvel article vient d\'être publié sur TransitX !<br><br>Titre de l\'article : ' . $titre_article . '<br><br>Connectez-vous pour le lire.<br><br>À très vite !';

    // Envoi de l'email
    $mail->send();
    echo 'L\'email a bien été envoyé !';
} catch (Exception $e) {
    echo "L'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
}
?>
