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
        <form class="auth-form" action="View/FrontOffice/index.php">
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" placeholder="Entrez votre email">
            </div>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" placeholder="Entrez votre mot de passe">
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
          <p>Vous n'avez pas de compte? <a href="register.php">S'inscrire</a></p>
        </div>
      </div>
    </div>
    <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>

  <script>
    // Simple login without authentication
    document.querySelector('.auth-form').addEventListener('submit', function (e) {
      e.preventDefault();
      window.location.href = 'View/FrontOffice/index.php';
    });
  </script>
</body>

</html>