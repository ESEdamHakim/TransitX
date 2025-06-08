<?php
require_once __DIR__ . '/../../assets/vendor/google-api-php-client/vendor/autoload.php';
require_once '../../../Controller/UserC.php';

require_once 'google_oauth_config.php';
session_start();

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri('http://localhost/TransitX/View/FrontOffice/user/google_callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die('Erreur lors de la connexion avec Google: ' . htmlspecialchars($token['error']));
    }
    $client->setAccessToken($token['access_token']);

    $oauth = new Google_Service_Oauth2($client);
    $google_user = $oauth->userinfo->get();

    $userController = new UserC();
    $user = $userController->getUserByEmail($google_user->email);

    if ($user) {
        // User exists, log them in
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_type'] = $user->getType();
        $_SESSION['user_name'] = $user->getNom() . ' ' . $user->getPrenom();
    } else {
        // Auto-register as Client
        $newUser = $userController->registerGoogleUser($google_user);
        if ($newUser) {
            $_SESSION['user_id'] = $newUser->getId();
            $_SESSION['user_type'] = $newUser->getType();
            $_SESSION['user_name'] = $newUser->getNom() . ' ' . $newUser->getPrenom();
        } else {
            die('Erreur lors de la création du compte Google.');
        }
    }

    header('Location: ../index.php');
    exit();
} else {
    echo "Erreur lors de la connexion avec Google.";
}
?>