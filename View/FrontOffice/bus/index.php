<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Services de Bus</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>
  <header class="landing-header">
    <div class="container">
      <div class="header-left">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="main-logo">
          <span class="logo-text">TransitX</span>
        </div>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="../index.php">Accueil</a></li>
          <li class="active"><a href="index.php">Bus</a></li>
          <li><a href="../colis/index.php">Colis</a></li>
          <li><a href="../covoiturage/index.php">Covoiturage</a></li>
          <li><a href="../blog/index.php">Blog</a></li>
          <li><a href="../reclamation/index.php">Réclamation</a></li>
        </ul>
      </nav>
      <div class="header-right">
        <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
        <button class="mobile-menu-btn">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <main>
    <section class="bus-hero">
      <div class="hero-content">
        <h1>Services de Bus</h1>
        <p>Découvrez nos itinéraires de bus écologiques pour vos déplacements quotidiens.</p>
      </div>
    </section>

    <section class="bus-search">
      <div class="container">
        <div class="section-header">
          <span class="badge">Recherche</span>
          <h2>Trouvez votre trajet</h2>
        </div>
        <div class="search-container">
          <form class="search-form">
            <div class="form-group">
              <label for="departure">Départ</label>
              <input type="text" id="departure" placeholder="Ville de départ">
            </div>
            <div class="form-group">
              <label for="arrival">Arrivée</label>
              <input type="text" id="arrival" placeholder="Ville d'arrivée">
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" id="date">
            </div>
            <button type="submit" class="btn btn-primary">
              Rechercher
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
    </section>

    <section class="popular-routes">
      <div class="container">
        <div class="section-header">
          <span class="badge">Trajets</span>
          <h2>Trajets populaires</h2>
          <p>Découvrez nos trajets les plus populaires et réservez dès maintenant.</p>
        </div>
        <div class="route-cards">
        </div>
      </div>
    </section>

    <section class="bus-info">
      <div class="container">
        <div class="service-item">
          <div class="service-content">
            <span class="badge">Écologie</span>
            <h3>Nos Bus Écologiques</h3>
            <p>Chez TransitX, nous nous engageons à réduire notre empreinte carbone en utilisant une flotte de bus écologiques. Nos véhicules sont équipés des dernières technologies pour assurer un confort optimal tout en respectant l'environnement.</p>
            <ul class="features-list">
              <li><i class="fas fa-leaf"></i> Émissions de CO₂ réduites</li>
              <li><i class="fas fa-wifi"></i> Wi-Fi gratuit à bord</li>
              <li><i class="fas fa-plug"></i> Prises électriques</li>
              <li><i class="fas fa-wheelchair"></i> Accessibilité PMR</li>
              <li><i class="fas fa-snowflake"></i> Climatisation</li>
            </ul>
          </div>
          <div class="service-image">
            <img src="../../assets/images/bus-service.jpg" alt="Eco-friendly Bus">
          </div>
        </div>
      </div>
    </section>

    <section class="bus-map">
      <div class="container">
        <div class="section-header">
          <span class="badge">Itinéraires</span>
          <h2>Nos Itinéraires</h2>
          <p>Consultez notre réseau de lignes de bus à travers le pays.</p>
        </div>
        <div class="map-container">
          <img src="../../assets/images/bus-map.jpg" alt="Bus Routes Map" class="route-map">
        </div>
      </div>
    </section>

    <section class="faq">
      <div class="container">
        <div class="section-header">
          <span class="badge">FAQ</span>
          <h2>Questions Fréquentes</h2>
          <p>Trouvez les réponses à vos questions sur nos services de bus.</p>
        </div>
        <div class="faq-container">
          <div class="faq-item">
            <div class="faq-question">
              <h3>Comment réserver un billet de bus ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Vous pouvez réserver un billet de bus directement sur notre site web ou via notre application mobile. Sélectionnez votre trajet, choisissez la date et l'heure, puis procédez au paiement.</p>
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <h3>Puis-je annuler mon billet ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Oui, vous pouvez annuler votre billet jusqu'à 24 heures avant le départ. Des frais d'annulation peuvent s'appliquer selon les conditions de votre tarif.</p>
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <h3>Les animaux sont-ils autorisés à bord ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Les petits animaux de compagnie sont autorisés s'ils sont transportés dans un sac ou une cage appropriée. Les chiens guides pour personnes malvoyantes sont toujours acceptés.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="main-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="footer-logo-img">
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
            <li><a href="index.php">Bus</a></li>
            <li><a href="../covoiturage/index.php">Covoiturage</a></li>
            <li><a href="../colis/index.php">Colis</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>À propos</h4>
          <ul>
            <li><a href="../about.php">Notre mission</a></li>
            <li><a href="../blog/index.php">Blog</a></li>
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
    document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
      document.querySelector('.main-nav').classList.toggle('active');
    });

    // FAQ toggle
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
      const question = item.querySelector('.faq-question');
      question.addEventListener('click', () => {
        item.classList.toggle('active');
      });
    });

    // Ensure dashboard button is visible
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';
  </script>
</body>
</html>
