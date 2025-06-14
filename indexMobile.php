<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Mobilité Urbaine Durable</title>
  <link rel="stylesheet" href="View/assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
</head>

<body>
  <header class="landing-header">
    <div class="container">
      <div class="header-left">
        <div class="logo">
          <img src="View/assets/images/logo.png" alt="TransitX Logo" class="main-logo">
          <span class="logo-text">TransitX</span>
        </div>
      </div>
      <nav class="main-nav">
        <ul>
          <li class="active"><a href="index.php">Accueil</a></li>
          <li><a href="#">Bus</a></li>
          <li><a href="#">Colis</a></li>
          <li><a href="#">Covoiturage</a></li>
          <a href="loginMobile.php" class="btn btn-primary logout-btn">Se Connecter</a>
          <a href="View/FrontOffice/user/register.php" class="btn btn-outline">S'identifier</a>

        </ul>
      </nav>
      <div class="header-right">

        <button class="mobile-menu-btn">
          <i class="fas fa-bars"></i>
        </button>
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
          <img src="View/assets/images/hero-image.jpg" alt="TransitX Sustainable Mobility">
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
            <img src="View/assets/images/covoiturage-service.jpg" alt="Covoiturage TransitX">
          </div>
        </div>

        <!-- Colis Service -->
        <div class="service-item reverse">
          <div class="service-image">
            <img src="View/assets/images/colis-service.jpg" alt="Livraison de colis TransitX">
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
            <img src="View/assets/images/bus-service.jpg" alt="Transport en commun TransitX">
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer class="main-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-logo">
          <img src="View/assets/images/logo.png" alt="TransitX Logo" class="footer-logo-img">
          <span>TransitX</span>
        </div>
        <div class="footer-slogan">
          <p>Move Green, Live Clean</p>
        </div>
        <div class="footer-social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-middle">
        <div class="footer-column">
          <h4>Services</h4>
          <ul>
            <li><a href="#">Bus</a></li>
            <li><a href="#">Covoiturage</a></li>
            <li><a href="#">Colis</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>À propos</h4>
          <ul>
            <li><a href="#">Notre mission</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Légal</h4>
          <ul>
            <li><a href="#">Conditions d'utilisation</a></li>
            <li><a href="#">Politique de confidentialité</a></li>
            <li><a href="#">Cookies</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Contact</h4>
          <ul>
            <li><i class="fas fa-map-marker-alt"></i> 123 Avenue Habib Bourguiba, Tunis</li>
            <li><i class="fas fa-phone"></i> +216 26 216 216</li>
            <li><i class="fas fa-envelope"></i> contact@transitx.com</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 TransitX. Tous droits réservés.</p>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Profile dropdown functionality
      const profileToggle = document.getElementById('profileToggle');
      const profileDropdown = document.getElementById('profileDropdown');
      const userProfileDropdown = document.getElementById('userProfileDropdown');

      if (profileToggle && profileDropdown) {
        profileToggle.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          userProfileDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
          if (!userProfileDropdown.contains(e.target)) {
            userProfileDropdown.classList.remove('show');
          }
        });
      }
      // Mobile menu toggle
      document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
        document.querySelector('.main-nav').classList.toggle('active');
      });

      // Ensure dashboard button is visible
      document.querySelector('.dashboard-btn').style.display = 'inline-flex';
      document.querySelector('.logout-btn').style.display = 'inline-flex';
      // ...existing code...
      // Mobile menu toggle
      const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
      const mainNav = document.querySelector('.main-nav');
      mobileMenuBtn.addEventListener('click', function () {
        mainNav.classList.toggle('active');
      });
      // Close menu when clicking a link (for better UX)
      mainNav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
          mainNav.classList.remove('active');
        });
      });
      // ...existing code...
    });
  </script>

</body>

</html>