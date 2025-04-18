<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Covoiturage</title>
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
          <li><a href="../bus/index.php">Bus</a></li>
          <li><a href="../colis/index.php">Colis</a></li>
          <li class="active"><a href="index.php">Covoiturage</a></li>
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
    <section class="covoiturage-hero">
      <div class="hero-content">
        <h1>Covoiturage Écologique</h1>
        <p>Partagez vos trajets, économisez de l'argent et réduisez votre empreinte carbone.</p>
        <div class="hero-buttons">
          <a href="#search-rides" class="btn btn-primary">Rechercher un trajet</a>
          <a href="#create-ride" class="btn btn-outline">Proposer un trajet</a>
        </div>
      </div>
    </section>

    <section id="search-rides" class="search-section">
      <div class="container">
        <div class="section-header">
          <span class="badge">Recherche</span>
          <h2>Trouvez un trajet</h2>
          <p>Recherchez parmi les trajets disponibles proposés par notre communauté.</p>
        </div>
        <div class="search-container">
          <form class="search-form">
            <div class="form-group">
              <label for="departure">Départ</label>
              <input type="text" id="departure" placeholder="Ville de départ">
            </div>
            <div class="form-group">
              <label for="destination">Destination</label>
              <input type="text" id="destination" placeholder="Ville d'arrivée">
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" id="date">
            </div>
            <div class="form-group">
              <label for="passengers">Voyageurs</label>
              <select id="passengers">
                <option value="1">1 voyageur</option>
                <option value="2">2 voyageurs</option>
                <option value="3">3 voyageurs</option>
                <option value="4">4 voyageurs</option>
              </select>
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
          <p>Découvrez les trajets les plus demandés par notre communauté.</p>
        </div>
        <div class="route-cards">
          <div class="route-card">
            <div class="route-cities">
              <span class="departure">Tunis</span>
              <i class="fas fa-arrow-right"></i>
              <span class="arrival">Sousse</span>
            </div>
            <div class="route-details">
              <div class="departure-date">
                <i class="fas fa-calendar"></i>
                <span>Aujourd'hui, 18h00</span>
              </div>
              <div class="available-seats">
                <i class="fas fa-users"></i>
                <span>3 places disponibles</span>
              </div>
              <div class="price">20 TND</div>
            </div>
            <div class="driver-info">
              <img src="../../assets/images/placeholder-user.png" alt="Driver" class="driver-img">
              <div class="driver-rating">
                <i class="fas fa-star"></i>
                <span>4.8</span>
              </div>
            </div>
            <a href="#" class="btn btn-primary">Réserver</a>
          </div>

          <div class="route-card">
            <div class="route-cities">
              <span class="departure">Sousse</span>
              <i class="fas fa-arrow-right"></i>
              <span class="arrival">Sfax</span>
            </div>
            <div class="route-details">
              <div class="departure-date">
                <i class="fas fa-calendar"></i>
                <span>Demain, 10h30</span>
              </div>
              <div class="available-seats">
                <i class="fas fa-users"></i>
                <span>2 places disponibles</span>
              </div>
              <div class="price">15 TND</div>
            </div>
            <div class="driver-info">
              <img src="../../assets/images/placeholder-user.png" alt="Driver" class="driver-img">
              <div class="driver-rating">
                <i class="fas fa-star"></i>
                <span>4.6</span>
              </div>
            </div>
            <a href="#" class="btn btn-primary">Réserver</a>
          </div>

          <div class="route-card">
            <div class="route-cities">
              <span class="departure">Hammamet</span>
              <i class="fas fa-arrow-right"></i>
              <span class="arrival">Monastir</span>
            </div>
            <div class="route-details">
              <div class="departure-date">
                <i class="fas fa-calendar"></i>
                <span>Après-demain, 14h00</span>
              </div>
              <div class="available-seats">
                <i class="fas fa-users"></i>
                <span>1 place disponible</span>
              </div>
              <div class="price">12 TND</div>
            </div>
            <div class="driver-info">
              <img src="../../assets/images/placeholder-user.png" alt="Driver" class="driver-img">
              <div class="driver-rating">
                <i class="fas fa-star"></i>
                <span>4.9</span>
              </div>
            </div>
            <a href="#" class="btn btn-primary">Réserver</a>
          </div>
        </div>
      </div>
    </section>

    <section id="create-ride" class="create-ride-section">
      <div class="container">
        <div class="section-header">
          <span class="badge">Proposer</span>
          <h2>Proposer un trajet</h2>
          <p>Partagez votre trajet et contribuez à une mobilité plus durable.</p>
        </div>
        <div class="service-item">
          <div class="service-content">
            <form class="create-ride-form">
              <div class="form-row">
                <div class="form-group">
                  <label for="start-point">Point de départ</label>
                  <input type="text" id="start-point" placeholder="Ville de départ">
                </div>
                <div class="form-group">
                  <label for="end-point">Destination</label>
                  <input type="text" id="end-point" placeholder="Ville d'arrivée">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="ride-date">Date</label>
                  <input type="date" id="ride-date">
                </div>
                <div class="form-group">
                  <label for="ride-time">Heure</label>
                  <input type="time" id="ride-time">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="seats">Places disponibles</label>
                  <select id="seats">
                    <option value="1">1 place</option>
                    <option value="2">2 places</option>
                    <option value="3">3 places</option>
                    <option value="4">4 places</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="price-per-seat">Prix par place (TND)</label>
                  <input type="number" id="price-per-seat" min="1" step="1">
                </div>
              </div>
              <div class="form-group">
                <label for="vehicle">Véhicule</label>
                <select id="vehicle">
                  <option value="">Sélectionner un véhicule</option>
                  <option value="car">Voiture</option>
                  <option value="van">Minivan</option>
                  <option value="moto">Moto</option>
                </select>
              </div>
              <div class="form-group">
                <label for="ride-details">Détails supplémentaires</label>
                <textarea id="ride-details" rows="3" placeholder="Précisez les détails de votre trajet (arrêts, bagages autorisés, etc.)"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">
                Publier le trajet
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
          <div class="service-image">
            <img src="../../assets/images/covoiturage-service.jpg" alt="Proposer un covoiturage">
          </div>
        </div>
      </div>
    </section>

    <section class="how-it-works">
      <div class="container">
        <div class="section-header">
          <span class="badge">Fonctionnement</span>
          <h2>Comment ça marche</h2>
          <p>Découvrez comment fonctionne notre service de covoiturage en 3 étapes simples.</p>
        </div>
        <div class="steps-container">
          <div class="step">
            <div class="step-icon">
              <i class="fas fa-search"></i>
            </div>
            <h3>Recherchez</h3>
            <p>Trouvez un trajet qui correspond à vos besoins en quelques clics.</p>
          </div>
          <div class="step">
            <div class="step-icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <h3>Réservez</h3>
            <p>Réservez votre place et payez en ligne en toute sécurité.</p>
          </div>
          <div class="step">
            <div class="step-icon">
              <i class="fas fa-car"></i>
            </div>
            <h3>Voyagez</h3>
            <p>Rejoignez votre conducteur au point de rendez-vous et profitez du trajet.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="benefits">
      <div class="container">
        <div class="section-header">
          <span class="badge">Avantages</span>
          <h2>Avantages du Covoiturage</h2>
          <p>Découvrez pourquoi le covoiturage est bénéfique pour vous et pour l'environnement.</p>
        </div>
        <div class="benefits-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-leaf"></i>
            </div>
            <h3>Écologique</h3>
            <p>Réduisez votre empreinte carbone en partageant un véhicule plutôt que de conduire seul.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-euro-sign"></i>
            </div>
            <h3>Économique</h3>
            <p>Partagez les frais de transport et économisez sur vos déplacements quotidiens.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <h3>Social</h3>
            <p>Rencontrez de nouvelles personnes et rendez vos trajets plus agréables.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-road"></i>
            </div>
            <h3>Moins de Trafic</h3>
            <p>Contribuez à réduire la congestion routière dans votre ville.</p>
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
            <li><a href="../bus/index.php">Bus</a></li>
            <li><a href="index.php">Covoiturage</a></li>
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

    // Ensure dashboard button is visible
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';
  </script>
</body>
</html>
