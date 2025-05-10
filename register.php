<?php
require_once  'Controller/clientC.php';

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
            header('Location: index.php'); // Redirect after successful signup
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
  <link rel="stylesheet" href="View/assets/css/main.css">
  <link rel="stylesheet" href="View/assets/css/auth.css">
</head>
<body>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-card">
        <div class="auth-header">
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
              <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
              <label for="lastname">Nom</label>
              <input type="text" id="lastname" name="lastname" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
          </div>

          <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="tel" id="phone" name="phone">
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
          </div>

          <div class="form-group">
            <label for="confirm-password">Confirmer le mot de passe</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
          </div>

          <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>

        <div class="auth-footer">
          <p>Vous avez déjà un compte? <a href="index.php">Se connecter</a></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
      if (document.getElementById('password').value !== document.getElementById('confirm-password').value) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
      }
    });
  </script>
</body>
</html>
