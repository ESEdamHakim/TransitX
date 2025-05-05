<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/Controller/userC.php';

$userController = new UserC();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $hcaptcha_response = $_POST['h-captcha-response'] ?? '';
    
    // Verify hCaptcha first
    if (empty($hcaptcha_response)) {
        $error = "Veuillez compléter le captcha.";
    } else {
        $hcaptcha_secret = 'ES_5c4045e58ba8477298cf1864401501e5';
        $verify_url = 'https://hcaptcha.com/siteverify';
        
        $data = [
            'secret' => $hcaptcha_secret,
            'response' => $hcaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($verify_url, false, $context);
        $response_data = json_decode($result);
        
        if (!$response_data->success) {
            $error = "Échec de la vérification du captcha. Veuillez réessayer.";
        } else {
            // Proceed with login if captcha is valid
            $user = $userController->getUserByEmail($email);
            
            if ($user) {
                if (password_verify($password, $user->getPassword()) || 
                    $password === $user->getPassword()) {
                    
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user_type'] = $user->getType();
                    $_SESSION['user_name'] = $user->getNom() . ' ' . $user->getPrenom();

                    header('Location: View/' . 
                          ($user->getType() === 'employe' ? 'BackOffice' : 'FrontOffice') . 
                          '/index.php');
                    exit();
                }
            }
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Connexion</title>
  <link rel="stylesheet" href="View/assets/css/main.css">
  <link rel="stylesheet" href="View/assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-card">
        <div class="auth-header">
          <div class="logo-container">
            <img src="View/assets/images/logo.png" alt="TransitX Logo" class="auth-logo">
            <span class="logo-text">TransitX</span>
          </div>
          <h1>Connexion</h1>
          <p>Bienvenue sur TransitX. Veuillez vous connecter pour continuer.</p>
        </div>

        <form class="auth-form" method="post">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="Entrez votre email" required
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>
          </div>

          <div class="form-group">
            <div class="h-captcha" data-sitekey="3bde0e2e-31d0-4140-bf90-10b6a89c299c"></div>
          </div>

          <div class="form-options">
            <div class="remember-me">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Se souvenir de moi</label>
            </div>
            <a href="forgot-password.php" class="forgot-password">Mot de passe oublié?</a>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>

        <div class="auth-footer">
          <p>Vous n'avez pas de compte? <a href="/user/register.php">S'inscrire</a></p>
        </div>
      </div>
    </div>
    <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>

  <!-- Load hCaptcha API at the end of the body -->
  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</body>
</html>