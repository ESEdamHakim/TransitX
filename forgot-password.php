<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Mot de passe oublié</title>
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
          <h1>Mot de passe oublié</h1>
          <p>Entrez votre adresse email pour réinitialiser votre mot de passe.</p>
        </div>
        <form class="auth-form" action="login.php">
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" placeholder="Entrez votre email">
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Réinitialiser le mot de passe</button>
        </form>
        <div class="auth-footer">
          <p>Vous vous souvenez de votre mot de passe? <a href="login.php">Se connecter</a></p>
        </div>
      </div>
    </div>
    <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>

  <script>
    // Simple redirect
    document.querySelector('.auth-form').addEventListener('submit', function (e) {
      e.preventDefault();
      alert('Un email de réinitialisation a été envoyé à votre adresse email.');
      window.location.href = 'login.php';
    });
  </script>
</body>

</html>