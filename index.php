<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!empty($_POST['user_id'])) {
    $_SESSION['user_id'] = $_POST['user_id']; // Store the typed ID in session
    header("Location: View/FrontOffice/index.php");
    exit();
  } else {
    $error = "Veuillez saisir un ID valide.";
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
          <p>Veuillez entrer votre identifiant pour continuer.</p>
        </div>

        <?php if (!empty($error)): ?>
          <p style="color:red;text-align:center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="">
          <div class="form-group">
            <label for="user_id">ID utilisateur</label>
            <div class="input-with-icon">
              <i class="fas fa-id-card"></i>
              <input type="text" id="user_id" name="user_id" placeholder="Entrez votre ID" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Se connecter</button>

          <div class="auth-footer">
            <p>Vous n'avez pas de compte? <a href="register.php">S'inscrire</a></p>
          </div>
        </form>
      </div>
    </div>
    <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>
</body>

</html>
