<?php
require_once __DIR__ . '/../../assets/vendor/google-api-php-client/vendor/autoload.php';
require_once 'google_oauth_config.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);

$client->setRedirectUri('http://localhost/TransitX/View/FrontOffice/user/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

header('Location: ' . $client->createAuthUrl());
exit;