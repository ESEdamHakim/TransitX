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
  $face_descriptor = isset($_POST['face_descriptor']) && $_POST['face_descriptor'] ? $_POST['face_descriptor'] : null;

  // Validate that passwords match
  if ($password !== $confirm_password) {
    $error = "Les mots de passe ne correspondent pas.";
  } else {
    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Create new client instance
    $client = new Client($nom, $prenom, $email, $telephone, $date_naissance);
    $client->setPassword($hashedPassword);
    $client->setFaceDescriptor($face_descriptor);

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
              <input type="password" id="confirm-password" name="confirm-password"
                placeholder="Confirmez votre mot de passe" required>
            </div>
          </div>

          <!-- Registration face capture -->
          <div class="form-group" style="text-align:center;">
            <label>Enregistrez votre visage (optionnel)</label><br>
            <video id="regFaceVideo" width="220" height="160" autoplay
              style="border-radius:10px; margin-bottom:8px;"></video><br>
            <button type="button" id="regCaptureFaceBtn" class="btn btn-secondary" style="margin-bottom:8px;">Capturer
              le visage</button>
            <canvas id="regFaceCanvas" width="220" height="160" style="display:none;"></canvas>
            <input type="hidden" name="face_descriptor" id="face_descriptor">
            <div id="regFaceStatus" style="font-size:13px; color:#2d8659; margin-top:4px;"></div>
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

  <!-- Face ID Modal -->
  <div id="faceIdModal"
    style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
    <div
      style="background:white; padding:24px; border-radius:12px; max-width:350px; margin:auto; text-align:center; position:relative;">
      <h3>Connexion Face ID</h3>
      <video id="faceVideo" width="280" height="210" autoplay style="border-radius:10px; margin-bottom:10px;"></video>
      <br>
      <button id="captureFaceBtn" class="btn btn-primary" style="margin-bottom:10px;">Capturer et se connecter</button>
      <button id="closeFaceModal" class="btn btn-secondary" type="button">Annuler</button>
      <canvas id="faceCanvas" width="280" height="210" style="display:none;"></canvas>
      <form id="faceLoginForm" method="POST" action="face_login.php" style="display:none;">
        <input type="hidden" name="face_image" id="face_image">
      </form>
    </div>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

  <script defer>
    document.addEventListener('DOMContentLoaded', async function () {
      let faceStream = null;
      let faceDescriptor = null;

      const faceVideo = document.getElementById('regFaceVideo');
      const faceCanvas = document.getElementById('regFaceCanvas');
      const captureFaceBtn = document.getElementById('regCaptureFaceBtn');
      const faceDescriptorInput = document.getElementById('face_descriptor');
      const faceStatus = document.getElementById('regFaceStatus');

      faceStatus.textContent = "Chargement des modèles de reconnaissance...";
      try {
        await faceapi.nets.tinyFaceDetector.loadFromUri('./models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('./models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('./models');
        faceStatus.textContent = "Modèles chargés. Vous pouvez capturer votre visage.";
        faceStatus.style.color = "#2d8659";
      } catch (err) {
        faceStatus.textContent = "Erreur lors du chargement des modèles : " + err;
        faceStatus.style.color = "#c0392b";
        console.error("Erreur lors du chargement des modèles :", err);
        return;
      }

      try {
        faceStream = await navigator.mediaDevices.getUserMedia({ video: true });
        faceVideo.srcObject = faceStream;
      } catch (err) {
        faceStatus.textContent = "Webcam non disponible ou refusée.";
        faceStatus.style.color = "#c0392b";
        return;
      }

      captureFaceBtn.onclick = async function () {
        faceCanvas.getContext('2d').drawImage(faceVideo, 0, 0, faceCanvas.width, faceCanvas.height);
        faceStatus.textContent = "Analyse du visage...";
        try {
          const detection = await faceapi.detectSingleFace(faceCanvas, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
          if (detection && detection.descriptor) {
            faceDescriptor = Array.from(detection.descriptor);
            faceDescriptorInput.value = JSON.stringify(faceDescriptor);
            faceStatus.textContent = "Visage enregistré avec succès !";
            faceStatus.style.color = "#2d8659";
          } else {
            faceStatus.textContent = "Aucun visage détecté. Essayez à nouveau.";
            faceStatus.style.color = "#c0392b";
          }
        } catch (err) {
          faceStatus.textContent = "Erreur lors de l'analyse du visage.";
          faceStatus.style.color = "#c0392b";
        }
      };
    });
  </script>
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