<?php
// Set session settings before starting the session
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_lifetime', 1800); // 30 minutes
ini_set('session.use_strict_mode', 1); // Enhanced security

// Start the session
session_start();
session_regenerate_id(true); // Prevent session fixation

require_once 'Controller/userC.php';

// Function to store attempts in session
function storeAttempt($ip)
{
  if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
  }

  if (!isset($_SESSION['login_attempts'][$ip])) {
    $_SESSION['login_attempts'][$ip] = [
      'count' => 1,
      'last_attempt' => time(),
      'is_banned' => false
    ];
  } else {
    $_SESSION['login_attempts'][$ip]['count']++;
    $_SESSION['login_attempts'][$ip]['last_attempt'] = time();

    if ($_SESSION['login_attempts'][$ip]['count'] >= 3) {
      $_SESSION['login_attempts'][$ip]['is_banned'] = true;
    }
  }

  return $_SESSION['login_attempts'][$ip];
}

// Function to check ban status
function checkBanStatus($ip)
{
  if (!isset($_SESSION['login_attempts'][$ip])) {
    return ['banned' => false, 'remaining' => 0];
  }

  $attempt = $_SESSION['login_attempts'][$ip];
  if ($attempt['is_banned']) {
    $elapsed = time() - $attempt['last_attempt'];
    $remaining = 15 - $elapsed;

    if ($remaining <= 0) {
      // Ban expired
      unset($_SESSION['login_attempts'][$ip]);
      return ['banned' => false, 'remaining' => 0];
    }

    return ['banned' => true, 'remaining' => $remaining];
  }

  return ['banned' => false, 'remaining' => 0];
}

$userController = new UserC();
$error = '';

// Start with a clean session if it's expired
if (!isset($_SESSION['login_attempts']) || !is_array($_SESSION['login_attempts'])) {
  $_SESSION['login_attempts'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_ip = $_SERVER['REMOTE_ADDR'];
  $ban_status = checkBanStatus($user_ip);

  if ($ban_status['banned']) {
    $remaining = $ban_status['remaining'];
    $error = "Trop de tentatives. Veuillez attendre {$remaining} secondes.";
    die("<script>
            alert('Vous êtes temporairement bloqué. Veuillez attendre {$remaining} secondes.');
            setTimeout(function() { window.location.reload(); }, {$remaining} * 1000);
            </script>");
  }

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
        if (password_verify($password, $user->getPassword())) {
          // Successful login - reset attempts
          if (isset($_SESSION['login_attempts'][$user_ip])) {
            unset($_SESSION['login_attempts'][$user_ip]);
          }

          $_SESSION['user_id'] = $user->getId();
          $_SESSION['user_type'] = $user->getType();
          $_SESSION['user_name'] = $user->getNom() . ' ' . $user->getPrenom();

          header('Location: View/FrontOffice/index.php');
          exit();
        }
      }

      // Increment attempt counter
      $current_attempt = storeAttempt($user_ip);

      if ($current_attempt['count'] >= 3) {
        // User just got banned
        echo "<div style='position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;'>
                      <div style='background:#ff4444;color:white;padding:20px;border-radius:10px;text-align:center;'>
                        <h2>Accès temporairement bloqué</h2>
                        <p>Trop de tentatives incorrectes. Veuillez attendre <span id='countdown'>15</span> secondes.</p>
                        <div style='width:100%;height:4px;background:rgba(255,255,255,0.3);margin-top:10px;'>
                          <div id='progress' style='width:100%;height:100%;background:white;transition:width 1s linear;'></div>
                        </div>
                      </div>
                    </div>
                    <script>
                    let timeLeft = 15;
                    const countdown = document.getElementById('countdown');
                    const progress = document.getElementById('progress');
                    const timer = setInterval(() => {
                        timeLeft--;
                        countdown.textContent = timeLeft;
                        progress.style.width = (timeLeft / 15 * 100) + '%';
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            window.location.reload();
                        }
                    }, 1000);
                    </script>";
        exit();
      } else {
        $attempts_left = 3 - $current_attempt['count'];
        $error = "Email ou mot de passe incorrect. Il vous reste {$attempts_left} tentative" .
          ($attempts_left > 1 ? 's' : '') . " avant le blocage temporaire.";
      }
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
  <style>
    .cooldown-timer {
      background: #ff4444;
      color: white;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 15px;
      animation: pulse 1s infinite;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.02);
      }

      100% {
        transform: scale(1);
      }
    }

    .cooldown-progress {
      width: 100%;
      height: 4px;
      background: rgba(255, 255, 255, 0.3);
      margin-top: 5px;
      border-radius: 2px;
      overflow: hidden;
    }

    .cooldown-bar {
      height: 100%;
      background: white;
      width: 100%;
      transition: width 1s linear;
    }
  </style>
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
              <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe">
            </div>
            <div class="text-right" style="margin-top: 5px;">
              <a href="View/FrontOffice/user/reset_password.php" class="forgot-password"
                style="color: #86b391; text-decoration: none; font-size: 0.9em;">Mot de passe oublié ?</a>
            </div>
          </div>

          <div class="form-group">
            <div class="h-captcha" data-sitekey="3bde0e2e-31d0-4140-bf90-10b6a89c299c"></div>
          </div>
          <div class="form-row">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-right-to-bracket"></i> Se connecter
            </button>
            <!-- Face ID Login Button -->
            <button type="button" id="faceIdBtn" class="btn btn-primary btn-block">
              <i class="fas fa-face-smile"></i> Face ID
            </button>
            <a href="View/FrontOffice/user/google_login.php" class="forgot-password"
                style="color: #86b391; text-decoration: none; font-size: 0.9em;">Google Login</a>
          </div>
        </form>

        <div class="auth-footer">
          <p>Vous n'avez pas de compte? <a href="View/FrontOffice/user/register.php">S'inscrire</a></p>
        </div>
      </div>
    </div>
    <div class="auth-image">
      <div class="overlay"></div>
    </div>
  </div>

  <!-- Face ID Modal -->
  <div id="faceIdModal" class="faceid-modal-overlay">
    <div class="faceid-modal-content">
      <div class="faceid-modal-header">
        <h3>Connexion Face ID</h3>
      </div>
      <div id="faceIdError" class="faceid-error-message" style="display:none;"></div>
      <video id="loginFaceVideo" width="600" height="500" autoplay></video>
      <br>
      <div class="faceid-modal-buttons">
        <button id="loginCaptureFaceBtn" class="btn btn-primary">
          <i class="fas fa-camera"></i> Capturer et se connecter
        </button>
        <button id="closeFaceModal" class="btn btn-secondary" type="button">
          <i class="fas fa-xmark"></i> Annuler
        </button>
      </div>
      <canvas id="loginFaceCanvas" width="600" height="500" style="display:none;"></canvas>
      <input type="hidden" name="face_image" id="face_image">
    </div>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
  <script defer>
    window.addEventListener('DOMContentLoaded', async function () {
      const faceIdBtn = document.getElementById('faceIdBtn');
      const faceIdModal = document.getElementById('faceIdModal');
      const closeFaceModal = document.getElementById('closeFaceModal');
      const faceVideo = document.getElementById('loginFaceVideo');
      const captureFaceBtn = document.getElementById('loginCaptureFaceBtn');
      const faceCanvas = document.getElementById('loginFaceCanvas');
      let stream = null;

      // Load models (adjust path if needed)
      await faceapi.nets.tinyFaceDetector.loadFromUri('./View/FrontOffice/user/models');
      await faceapi.nets.faceLandmark68Net.loadFromUri('./View/FrontOffice/user/models');
      await faceapi.nets.faceRecognitionNet.loadFromUri('./View/FrontOffice/user/models');

      function showFaceIdError(msg) {
        const errorDiv = document.getElementById('faceIdError');
        errorDiv.textContent = msg;
        errorDiv.classList.add('show');
        errorDiv.style.display = 'block';
        setTimeout(() => {
          errorDiv.classList.remove('show');
          errorDiv.style.display = 'none';
        }, 3500); // Hide after 3.5 seconds
      }

      faceIdBtn.onclick = function () {
        faceIdModal.classList.add('show');
        navigator.mediaDevices.getUserMedia({ video: true }).then(s => {
          stream = s;
          faceVideo.srcObject = stream;
        });
      };

      closeFaceModal.onclick = function () {
        faceIdModal.classList.remove('show');
        if (stream) {
          stream.getTracks().forEach(track => track.stop());
        }
      };

      captureFaceBtn.onclick = async function () {
        console.log("Capture button clicked");
        faceCanvas.getContext('2d').drawImage(faceVideo, 0, 0, faceCanvas.width, faceCanvas.height);

        // Detect face and get descriptor
        const detection = await faceapi.detectSingleFace(faceCanvas, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        console.log("Detection result:", detection);

        if (!detection || !detection.descriptor) {
          showFaceIdError("Aucun visage détecté. Essayez à nouveau.");
          return;
        }

        // Fetch all user descriptors from backend
        const response = await fetch('./View/FrontOffice/user/get_face_descriptors.php');
        const users = await response.json();
        console.log("Fetched users:", users);

        // Find best match
        const queryDescriptor = new Float32Array(detection.descriptor);
        let bestMatch = null;
        let minDistance = 1;

        users.forEach(user => {
          if (!user.descriptor) return;
          const dbDescriptor = new Float32Array(user.descriptor);
          const distance = faceapi.euclideanDistance(queryDescriptor, dbDescriptor);
          if (distance < minDistance) {
            minDistance = distance;
            bestMatch = user;
          }
        });

        console.log("Best match:", bestMatch, "Distance:", minDistance);

        if (bestMatch && minDistance < 0.5) {
          await fetch('./View/FrontOffice/user/face_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: bestMatch.id })
          });
          window.location.href = 'View/FrontOffice/index.php';
        } else {
          showFaceIdError("Visage non reconnu.");
        }
      };
    });
  </script>

  <!-- Load hCaptcha API at the end of the body -->
  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</body>

</html>