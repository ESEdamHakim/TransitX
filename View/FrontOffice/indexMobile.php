<?php
session_start();
require_once __DIR__ . '/../../Controller/userC.php';

$userController = new UserC();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// For testing - use the first user from the list instead of session user
// Comment this out once testing is complete
$currentUser = null;
$currentUser = null;

if (isset($_SESSION['user_id'])) {
  $currentUser = $userController->showUser($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Mobilité Urbaine Durable</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="../assets/chatbot/chatbot.css">
</head>

<body>
  <?php include '../assets/chatbot/chatbot.php'; ?>
  <header class="landing-header">
    <div class="container">
      <div class="header-left">
        <div class="logo">
          <img src="../assets/images/logo.png" alt="TransitX Logo" class="main-logo">
          <span class="logo-text">TransitX</span>
        </div>
      </div>
      <nav class="main-nav">
        <ul>
          <li class="active"><a href="indexMobile.php">Accueil</a></li>
          <li><a href="bus/indexMobile.php">Bus</a></li>
          <li><a href="colis/indexMobile.php">Colis</a></li>
          <li><a href="covoiturage/indexMobile.php">Covoiturage</a></li>
          <div class="actions-container" style="text-align: right;">
            <?php include 'assets/php/indexprofileMobile.php'; ?>
          </div>
        </ul>
      </nav>
      <div class="header-right">
        <div class="actions">
          <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </header>

  <main>
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="container">
        <div class="hero-content">
          <h1>Move Green, Live Clean</h1>
          <p>TransitX est votre plateforme de mobilité urbaine durable. Réservez des trajets en covoiturage, envoyez des
            colis et contribuez à un avenir plus vert.</p>
          <div class="hero-buttons">
            <a href="covoiturage/index.php" class="btn btn-primary">
              Réserver un trajet
              <i class="fas fa-arrow-right"></i>
            </a>
            <a href="colis/index.php" class="btn btn-outline">Envoyer un colis</a>
          </div>
        </div>
        <div class="hero-image">
          <img src="../assets/images/hero-image.jpg" alt="TransitX Sustainable Mobility">
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
      <div class="container">
        <div class="feature-cards">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <h3>Covoiturage</h3>
            <p>Partagez vos trajets quotidiens et réduisez votre empreinte carbone tout en économisant.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-box"></i>
            </div>
            <h3>Livraison de Colis</h3>
            <p>Envoyez vos colis rapidement et en toute sécurité avec notre service de livraison écologique.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-bus"></i>
            </div>
            <h3>Transport en Commun</h3>
            <p>Consultez les horaires des bus et planifiez vos déplacements en transport en commun.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits-section">
      <div class="container">
        <div class="section-header">
          <span class="badge">Nos avantages</span>
          <h2>Pourquoi choisir TransitX ?</h2>
          <p>Découvrez les avantages qui font de TransitX la plateforme de mobilité urbaine préférée des utilisateurs.
          </p>
        </div>
        <div class="benefits-grid">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="benefit-content">
              <h3>Gain de temps</h3>
              <p>Réservez vos trajets et envoyez vos colis en quelques clics, sans attente ni paperasse.</p>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <div class="benefit-content">
              <h3>Économies</h3>
              <p>Réduisez vos frais de transport grâce au covoiturage et à nos tarifs compétitifs.</p>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div class="benefit-content">
              <h3>Sécurité</h3>
              <p>Profitez d'un système de vérification des utilisateurs et de suivi en temps réel.</p>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="benefit-content">
              <h3>Fiabilité</h3>
              <p>Des services ponctuels et fiables, avec des notifications en cas de retard ou d'imprévu.</p>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="benefit-content">
              <h3>Géolocalisation</h3>
              <p>Suivez vos colis et vos trajets en temps réel grâce à notre système de géolocalisation.</p>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-truck"></i>
            </div>
            <div class="benefit-content">
              <h3>Livraison rapide</h3>
              <p>Bénéficiez de délais de livraison optimisés grâce à notre réseau de transporteurs.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
      <div class="container">
        <div class="section-header">
          <h2>Nos Services</h2>
          <p>Découvrez nos solutions de mobilité urbaine durable pour tous vos besoins de déplacement et de livraison.
          </p>
        </div>

        <!-- Covoiturage Service -->
        <div class="service-item">
          <div class="service-content">
            <span class="badge">Covoiturage</span>
            <h3>Partagez vos trajets, réduisez vos coûts</h3>
            <p>Notre service de covoiturage vous permet de partager vos trajets quotidiens ou occasionnels avec d'autres
              utilisateurs, réduisant ainsi vos coûts de transport et votre empreinte carbone.</p>
            <a href="covoiturage/index.php" class="btn btn-primary">
              Réserver un trajet
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <div class="service-image">
            <img src="../assets/images/covoiturage-service.jpg" alt="Covoiturage TransitX">
          </div>
        </div>

        <!-- Colis Service -->
        <div class="service-item reverse">
          <div class="service-image">
            <img src="../assets/images/colis-service.jpg" alt="Livraison de colis TransitX">
          </div>
          <div class="service-content">
            <span class="badge">Livraison de Colis</span>
            <h3>Envoyez vos colis rapidement et en toute sécurité</h3>
            <p>Notre service de livraison de colis vous permet d'envoyer vos colis rapidement et en toute sécurité, avec
              un suivi en temps réel et des tarifs compétitifs.</p>
            <a href="colis/index.php" class="btn btn-primary">
              Envoyer un colis
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>

        <!-- Bus Service -->
        <div class="service-item">
          <div class="service-content">
            <span class="badge">Transport en Commun</span>
            <h3>Planifiez vos déplacements en transport en commun</h3>
            <p>Consultez les horaires des bus et planifiez vos déplacements en transport en commun. Réservez vos billets
              en ligne et évitez les files d'attente.</p>
            <a href="bus/index.php" class="btn btn-primary">
              Consulter les horaires
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <div class="service-image">
            <img src="../assets/images/bus-service.jpg" alt="Transport en commun TransitX">
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../assets/indexfooter.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="../assets/chatbot/chatbot.js"> </script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Mobile menu toggle
      document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
        document.querySelector('.main-nav').classList.toggle('active');
      });

      // Ensure dashboard button is visible
      document.querySelector('.dashboard-btn').style.display = 'inline-flex';
      document.querySelector('.logout-btn').style.display = 'inline-flex';
    });
  </script>
  <script src="assets/js/profile.js"></script>

</body>

</html>