<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/Controller/userC.php';

$userController = new UserC();

// In your login page (index.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $user = $userController->getUserByEmail($email);

  if ($user) {
      // Debug output (remove in production)
      error_log("User found. Type: " . $user->getType());
      error_log("Input password: " . $password);
      error_log("Stored password: " . $user->getPassword());

      // First try password_verify, then fallback to plain text comparison
      // (Remove the plain text fallback after fixing all passwords)
      if (password_verify($password, $user->getPassword()) || 
          $password === $user->getPassword()) {
          
          $_SESSION['user_id'] = $user->getId();
          $_SESSION['user_type'] = $user->getType();
          $_SESSION['user_name'] = $user->getNom() . ' ' . $user->getPrenom();

          // Redirect based on user type
          header('Location: View/' . 
                ($user->getType() === 'employe' ? 'BackOffice' : 'FrontOffice') . 
                '/index.php');
          exit();
      }
  }
  
  $error = "Email ou mot de passe incorrect.";
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
          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>
          </div>

          <div class="form-options">
            <div class="remember-me">
              <input type="checkbox" id="remember">
              <label for="remember">Se souvenir de moi</label>
            </div>
            <a href="forgot-password.php" class="forgot-password">Mot de passe oublié?</a>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
          <div class="social-login">
            <p>Ou connectez-vous avec</p>
            <div class="social-buttons">
              <button type="button" class="social-btn facebook">
                <i class="fab fa-facebook-f"></i>
              </button>
              <button type="button" class="social-btn google">
                <i class="fab fa-google"></i>
              </button>
              <button type="button" class="social-btn twitter">
                <i class="fab fa-twitter"></i>
              </button>
            </div>
          </div>
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
</body>
</html>
