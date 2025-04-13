<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Inscription</title>
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
          <h1>Inscription</h1>
          <p>Créez votre compte TransitX pour profiter de tous nos services.</p>
        </div>
        <form class="auth-form" action="login.php">
          <div class="form-row">
            <div class="form-group">
              <label for="firstname">Prénom</label>
              <div class="input-with-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="firstname" placeholder="Entrez votre prénom">
              </div>
            </div>
            <div class="form-group">
              <label for="lastname">Nom</label>
              <div class="input-with-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="lastname" placeholder="Entrez votre nom">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" placeholder="Entrez votre email">
            </div>
          </div>
          <div class="form-group">
            <label for="phone">Téléphone</label>
            <div class="input-with-icon">
              <i class="fas fa-phone"></i>
              <input type="tel" id="phone" placeholder="Entrez votre numéro de téléphone">
            </div>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" placeholder="Créez un mot de passe">
            </div>
          </div>
          <div class="form-group">
            <label for="confirm-password">Confirmer le mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="confirm-password" placeholder="Confirmez votre mot de passe">
            </div>
          </div>
          <div class="form-options">
            <div class="remember-me">
              <input type="checkbox" id="terms">
              <label for="terms">J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a></label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
          <div class="social-login">
            <p>Ou inscrivez-vous avec</p>
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
          <p>Vous avez déjà un compte? <a href="login.php">Se connecter</a></p>
        </div>
      </div>
    </div>
    <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>

  <script>
    // Simple registration redirect
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
      e.preventDefault();
      window.location.href = 'login.php';
    });
  </script>
</body>
</html>
