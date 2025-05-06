<?php
include("../../Controller/buscontroller.php");
$user_id = 1;
$controller = new BusController();
$notifications = $controller->getNotificationsForUser($user_id);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Mobilité Urbaine Durable</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>

<body>
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
          <li class="active"><a href="index.php">Accueil</a></li>
          <li><a href="bus/index.php">Bus</a></li>
          <li><a href="colis/index.php">Colis</a></li>
          <li><a href="covoiturage/index.php">Covoiturage</a></li>
          <li><a href="blog/index.php">Blog</a></li>
          <li><a href="reclamation/index.php">Réclamation</a></li>
        </ul>
      </nav>
      <div class="header-right">
        <a href="../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <a href="../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>

        <!-- Notification Button -->
        <div class="notification-container">
          <button id="notifBtn"
            class="relative p-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fa-regular fa-bell text-2xl" style="color: #86b391;"></i>
            <!-- Notification Badge -->
            <?php if (count($notifications) > 0): ?>
              <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full badge-pulse">
                <?= count($notifications) ?>
              </span>
            <?php endif; ?>
          </button>

          <!-- Notification Dropdown -->
          <div id="notifBox" class="notification-dropdown hidden">
            <div class="p-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
            </div>

            <div class="max-h-96 overflow-y-auto">
              <?php if (empty($notifications)): ?>
                <div class="p-4 text-center text-gray-500">Aucune notification</div>
              <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                  <div
                    class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 cursor-pointer"
                    data-id="<?= htmlspecialchars($notif['id']) ?>">
                    <div class="text-sm text-gray-800 notification-message">
                      <?php
                      // Format the message to enhance the emoji and styling
                      $message = htmlspecialchars($notif['message']);
                      // Trim any leading or trailing whitespace
                      $message = trim($message);
                      // Make the bus emoji larger
                      $message = preg_replace('/🚌/', '<span class="emoji">🚌</span>', $message, 1);
                      // Convert newlines to <br> tags
                      $message = nl2br($message);
                      // Make the first line (title) bold - improved regex to avoid extra spaces
                      $message = preg_replace('/^(.*?)(?:\n|$)/s', '<strong>$1</strong>', $message, 1);
                      // Output the formatted message
                      echo $message;
                      ?>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                      <?php
                      // Format the timestamp to be more readable
                      $timestamp = strtotime($notif['created_at']);
                      $now = time();
                      $diff = $now - $timestamp-3600;

                      if ($diff < 60) {
                        echo "À l'instant";
                      } elseif ($diff < 3600) {
                        $minutes = floor($diff / 60);
                        echo "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
                      } elseif ($diff < 86400) {
                        $hours = floor($diff / 3600);
                        echo "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
                      } elseif ($diff < 604800) {
                        $days = floor($diff / 86400);
                        echo "Il y a " . $days . " jour" . ($days > 1 ? 's' : '');
                      } else {
                        echo date('d/m/Y à H:i', $timestamp);
                      }
                      ?>
                    </p>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <script>
          // Get DOM elements
          const notifBtn = document.getElementById("notifBtn");
          const notifBox = document.getElementById("notifBox");
          const markAllBtn = document.getElementById("mark-all-read");

          // Toggle notification dropdown
          notifBtn.addEventListener("click", function (event) {
            event.stopPropagation();

            // Toggle between hidden and visible
            notifBox.classList.toggle("hidden");
            notifBox.classList.toggle("show");
          });

          // Close dropdown when clicking outside
          document.addEventListener("click", function (event) {
            if (!notifBtn.contains(event.target) && !notifBox.contains(event.target)) {
              notifBox.classList.add("hidden");
              notifBox.classList.remove("show");
            }
          });

          // Add click handler for individual notifications
          document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function () {
              const notifId = this.getAttribute('data-id');
              console.log('Clicked notification:', notifId);

            });
          });
        </script>

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

    <!-- Blog Preview Section -->
    <section class="blog-preview-section">
      <div class="container">
        <div class="section-header">
          <span class="badge">Blog</span>
          <h2>Actualités et conseils</h2>
          <p>Découvrez nos derniers articles sur la mobilité urbaine durable et les tendances du secteur.</p>
        </div>
        <div class="blog-cards">
          <div class="blog-card">
            <div class="blog-image">
              <img src="../assets/images/blog-1.jpg" alt="The Future of Sustainable Urban Mobility">
            </div>
            <div class="blog-date">
              <i class="fas fa-calendar"></i>
              <time datetime="2023-03-15">15 mars 2023</time>
            </div>
            <h3>The Future of Sustainable Urban Mobility</h3>
            <p>Exploring the latest trends and innovations in sustainable urban transportation and their impact on city
              planning.</p>
            <a href="blog/article.php" class="blog-link">
              Lire la suite
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <div class="blog-card">
            <div class="blog-image">
              <img src="../assets/images/blog-2.jpg" alt="Benefits of Carpooling for the Environment and Your Wallet">
            </div>
            <div class="blog-date">
              <i class="fas fa-calendar"></i>
              <time datetime="2023-04-01">1 avril 2023</time>
            </div>
            <h3>Benefits of Carpooling for the Environment and Your Wallet</h3>
            <p>Discover how carpooling can help you save money while reducing your environmental impact.</p>
            <a href="blog/article.php" class="blog-link">
              Lire la suite
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <div class="blog-card">
            <div class="blog-image">
              <img src="../assets/images/blog-3.jpg" alt="How Parcel Consolidation Reduces Carbon Emissions">
            </div>
            <div class="blog-date">
              <i class="fas fa-calendar"></i>
              <time datetime="2023-04-10">10 avril 2023</time>
            </div>
            <h3>How Parcel Consolidation Reduces Carbon Emissions</h3>
            <p>Learn how consolidating parcels for delivery can significantly reduce carbon emissions in the logistics
              industry.</p>
            <a href="blog/article.php" class="blog-link">
              Lire la suite
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
        <div class="blog-view-all">
          <a href="blog/index.php" class="btn btn-outline">
            Voir tous les articles
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>
  </main>

  <footer class="main-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-logo">
          <img src="../assets/images/logo.png" alt="TransitX Logo" class="footer-logo-img">
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
            <li><a href="bus/index.php">Bus</a></li>
            <li><a href="covoiturage/index.php">Covoiturage</a></li>
            <li><a href="colis/index.php">Colis</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>À propos</h4>
          <ul>
            <li><a href="about.php">Notre mission</a></li>
            <li><a href="blog/index.php">Blog</a></li>
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
            <li><i class="fas fa-phone"></i> +216 71 123 456</li>
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
    // Mobile menu toggle
    document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
      document.querySelector('.main-nav').classList.toggle('active');
    });

    // Ensure dashboard button is visible
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';
  </script>
</body>

</html>