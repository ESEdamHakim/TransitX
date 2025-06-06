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

          <button type="button" id="openRegFaceIdModal" class="btn btn-primary btn-block">
            <i class="fas fa-face-smile"></i> Enregistrer mon visage (Face ID)
          </button>
          <input type="hidden" name="face_descriptor" id="face_descriptor">

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

  <div id="regFaceIdModal" class="faceid-modal-overlay">
    <div class="faceid-modal-content">
      <div class="faceid-modal-header">
        <h3>Enregistrement Face ID</h3>
      </div>
      <div id="regFaceIdError" class="faceid-error-message" style="display:none;"></div>
      <video id="regFaceIdVideo" width="600" height="500" autoplay></video>
      <br>
      <div class="faceid-modal-buttons">
        <button id="regCaptureFaceIdBtn" class="btn btn-primary">
          <i class="fas fa-camera"></i> Capturer et enregistrer
        </button>
        <button id="closeRegFaceIdModal" class="btn btn-secondary" type="button">
          <i class="fas fa-xmark"></i> Annuler
        </button>
      </div>
      <canvas id="regFaceIdCanvas" width="600" height="500" style="display:none;"></canvas>
    </div>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

  <script defer>
    window.addEventListener('DOMContentLoaded', function () {
      // Elements
      const openRegFaceIdModal = document.getElementById('openRegFaceIdModal');
      const regFaceIdModal = document.getElementById('regFaceIdModal');
      const closeRegFaceIdModal = document.getElementById('closeRegFaceIdModal');
      const regFaceIdVideo = document.getElementById('regFaceIdVideo');
      const regCaptureFaceIdBtn = document.getElementById('regCaptureFaceIdBtn');
      const regFaceIdCanvas = document.getElementById('regFaceIdCanvas');
      const regFaceDescriptorInput = document.getElementById('face_descriptor');
      const regFaceIdError = document.getElementById('regFaceIdError');
      let regStream = null;

      function showRegFaceIdError(msg) {
        regFaceIdError.textContent = msg;
        regFaceIdError.classList.add('show');
        regFaceIdError.style.display = 'block';
        setTimeout(() => {
          regFaceIdError.classList.remove('show');
          regFaceIdError.style.display = 'none';
        }, 3500);
      }

      openRegFaceIdModal.onclick = function () {
        regFaceIdModal.classList.add('show');
        navigator.mediaDevices.getUserMedia({ video: true }).then(s => {
          regStream = s;
          regFaceIdVideo.srcObject = regStream;
        });
      };

      closeRegFaceIdModal.onclick = function () {
        regFaceIdModal.classList.remove('show');
        if (regStream) {
          regStream.getTracks().forEach(track => track.stop());
        }
      };

      regCaptureFaceIdBtn.onclick = async function () {
        regFaceIdCanvas.getContext('2d').drawImage(regFaceIdVideo, 0, 0, regFaceIdCanvas.width, regFaceIdCanvas.height);
        showRegFaceIdError("Analyse du visage...");
        try {
          await faceapi.nets.tinyFaceDetector.loadFromUri('./models');
          await faceapi.nets.faceLandmark68Net.loadFromUri('./models');
          await faceapi.nets.faceRecognitionNet.loadFromUri('./models');
          const detection = await faceapi.detectSingleFace(regFaceIdCanvas, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
          if (detection && detection.descriptor) {
            regFaceDescriptorInput.value = JSON.stringify(Array.from(detection.descriptor));
            showRegFaceIdError("Visage enregistré avec succès !");
            regFaceIdError.style.color = "#2d8659";
            setTimeout(() => {
              regFaceIdModal.classList.remove('show');
              if (regStream) regStream.getTracks().forEach(track => track.stop());
            }, 1200);
          } else {
            showRegFaceIdError("Aucun visage détecté. Essayez à nouveau.");
            regFaceIdError.style.color = "#c0392b";
          }
        } catch (err) {
          showRegFaceIdError("Erreur lors de l'analyse du visage.");
          regFaceIdError.style.color = "#c0392b";
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