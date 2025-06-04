<?php
require_once '../../../Controller/clientC.php';

$clientController = new ClientC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ensure all fields are captured correctly
  $nom = $_POST['firstname'];
  $prenom = $_POST['lastname'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm-password'];
  $telephone = $_POST['phone'];
  $date_naissance = !empty($_POST['date_naissance']) ? new DateTime($_POST['date_naissance']) : null;

  // Validate that passwords match
  if ($password !== $confirm_password) {
    $error = "Les mots de passe ne correspondent pas.";
  } else {
    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Create new client instance
    $client = new Client($nom, $prenom, $email, $telephone, $date_naissance);
    $client->setPassword($hashedPassword);

    // Attempt to add client to DB
    if ($clientController->addClient($client)) {
      header('Location: ../../../login.php'); // Redirect after successful signup
      exit();
    } else {
      $error = "Erreur lors de l'inscription.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Inscription</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="../../assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-card">
        <div class="auth-header">
          <div class="logo-container">
            <img src="../../assets/images/logo.png" alt="TransitX Logo" class="auth-logo">
            <span class="logo-text">TransitX</span>
          </div>
          <h1>Inscription</h1>
          <p>Créez votre compte TransitX pour profiter de tous nos services.</p>
        </div>

        <form class="auth-form" method="post">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="form-row">
            <div class="form-group">
              <label for="firstname">Prénom</label>
              <div class="input-with-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="firstname" name="firstname" placeholder="Entrez votre prénom" required>
              </div>
            </div>
            <div class="form-group">
              <label for="lastname">Nom</label>
              <div class="input-with-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="lastname" name="lastname" placeholder="Entrez votre nom" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
          </div>

          <div class="form-group">
            <label for="phone">Téléphone</label>
            <div class="input-with-icon">
              <i class="fas fa-phone"></i>
              <input type="tel" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone">
            </div>
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="Créez un mot de passe" required>
            </div>
          </div>

          <div class="form-group">
            <label for="confirm-password">Confirmer le mot de passe</label>
            <div class="input-with-icon">
              <i class="fas fa-lock"></i>
              <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmez votre mot de passe" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>

        <div class="auth-footer">
          <p>Vous avez déjà un compte? <a href="../../../login.php">Se connecter</a></p>
        </div>
      </div>
    </div>
     <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>

  <script>
    document.querySelector('.auth-form').addEventListener('submit', function (e) {
      if (document.getElementById('password').value !== document.getElementById('confirm-password').value) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
      }
    });
  </script>
</body>

</html>