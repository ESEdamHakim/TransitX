<?php
require_once __DIR__ . '/../../../Controller/vehiculeC.php';
require_once __DIR__ . '/../../../configuration/appConfig.php';
//$id_user = 2; // Replace this with the actual user ID from the session
$vehiculeController = new VehiculeC();
$vehicules = $vehiculeController->getVehiculesByUser($id_user);

?>
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
  <link rel="stylesheet" href="assets/css/weatherstyles.css">
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
          <li><a href="../vehicule/index.php">Véhicule</a></li>
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
          <a href="javascript:void(0)" id="openWeatherModal" class="btn btn-outline">Voir météo</a>
        </div>
      </div>
    </section>
    <!--for the search bar-->
    <section id="search-rides" class="search-section">
      <div class="container">
        <div class="section-header">
          <span class="badge">Recherche</span>
          <h2>Trouvez un trajet</h2>
          <p>Recherchez parmi les trajets disponibles proposés par notre communauté.</p>
        </div>
        <div class="search-container">
          <form class="search-form" action="index.php" method="GET">
            <div class="form-group">
              <label for="departure">Départ</label>
              <input type="text" id="departure" name="departure" placeholder="Ville de départ">
            </div>
            <div class="form-group">
              <label for="destination">Destination</label>
              <input type="text" id="destination" name="destination" placeholder="Ville d'arrivée">
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" id="date" name="date">
            </div>
            <button type="submit" class="btn btn-primary">
              Rechercher
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
        <!-- Include search results dynamically -->
        <div class="route-cards">
          <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['departure']) || isset($_GET['destination']) || isset($_GET['date']))): ?>
            <?php include 'searchCovoiturage.php'; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="popular-routes">
      <div class="container">
        <div class="section-header">
          <span class="badge">Trajets</span>
          <h2>Trajets </h2>
          <p>Découvrez les trajets proposés par notre communauté.</p>
        </div>
        <div class="route-cards">
          <?php include 'displaycovoiturage.php'; ?>
        </div>
      </div>
    </section>
    <section class="user-routes">
      <div class="container">
        <div class="section-header">
          <span class="badge">Vos Trajets</span>
          <h2>Vos Trajets </h2>
          <p>Voici les trajets que vous avez ajoutés récemment.</p>
        </div>
        <div class="route-cards">
          <?php include 'UserDisplayCovoiturage.php'; ?>
        </div>
      </div>
      <section id="create-ride" class="create-ride-section">
        <div class="container">
          <div class="section-header">
            <h2>Proposer un trajet</h2>
          </div>
          <form class="create-ride-form" action="addCovoiturage.php" method="POST"> <!--novalidate-->
            <div class="form-row">
              <div class="form-group">
                <label for="start-point">Point de départ</label>
                <input type="text" id="start-point" name="lieu_depart" placeholder="Ville de départ">
                <span id="start-point-error" class="error-message"></span>
              </div>
              <div class="form-group">
                <label for="end-point">Destination</label>
                <input type="text" id="end-point" name="lieu_arrivee" placeholder="Ville d'arrivée">
                <span id="end-point-error" class="error-message"></span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="ride-date-create">Date</label>
                <input type="date" id="ride-date-create" name="date_depart">
                <span id="ride-date-create-error" class="error-message"></span>
              </div>
              <div class="form-group">
                <label for="ride-time-create">Heure</label>
                <input type="time" id="ride-time-create" name="temps_depart">
                <span id="ride-time-create-error" class="error-message"></span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="seats">Places disponibles</label>
                <select id="seats" name="places_dispo">
                  <option value="1">1 place</option>
                  <option value="2">2 places</option>
                  <option value="3">3 places</option>
                  <option value="4">4 places</option>
                </select>
                <span id="seats-error" class="error-message"></span>
              </div>
              <div class="form-group">
                <label for="price-per-seat">Prix par place (TND)</label>
                <input type="number" id="price-per-seat" name="prix" step="1">
                <span id="price-error" class="error-message"></span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="accept-parcels">Accepte les colis</label>
                <select id="accept-parcels" name="accepte_colis">
                  <option value="oui">Oui</option>
                  <option value="non">Non</option>
                </select>
                <span id="accept-parcels-error" class="error-message"></span>
              </div>
              <div class="form-group">
                <label for="full-parcels">Colis complet</label>
                <select id="full-parcels" name="colis_complet">
                  <option value="oui">Oui</option>
                  <option value="non">Non</option>
                </select>
                <span id="full-parcels-error" class="error-message"></span>
              </div>
            </div>
            <div class="form-group">
              <label for="ride-details">Détails supplémentaires</label>
              <select id="details-options" class="form-control">
                <option value="">-- Sélectionnez une option --</option>
                <option value="Bagages légers uniquement.">Bagages légers uniquement.</option>
                <option value="Trajet non-fumeur.">Trajet non-fumeur.</option>
                <option value="Merci d’être ponctuel.">Merci d’être ponctuel.</option>
                <option value="Pas de retard accepté.">Pas de retard accepté.</option>
                <option value="other">Autre...</option>
              </select>
              <textarea id="ride-details" name="details"
                placeholder="Ajoutez des détails ou complétez l'option sélectionnée" class="form-control"
                style="margin-top: 10px;"></textarea>
              <span id="ride-details-error" class="error-message"></span>
            </div>
            <div class="form-group">
              <label for="id_vehicule">Sélectionnez un véhicule</label>
              <?php if (!empty($vehicules)): ?>
                <select id="id_vehicule" name="id_vehicule">
                  <option value="">-- Sélectionnez un véhicule --</option>
                  <?php foreach ($vehicules as $vehicule): ?>
                    <option value="<?= htmlspecialchars($vehicule['id_vehicule']) ?>">
                      <?= htmlspecialchars($vehicule['matricule']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <span id="id-vehicule-error" class="error-message"></span>
              <?php else: ?>
                <p>Vous n'avez pas encore ajouté de véhicule.</p>
                <a href="../vehicule/index.php" class="btn btn-primary">Ajouter véhicule</a>
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">
              Publier le trajet
              <i class="fas fa-paper-plane"></i>
            </button>
          </form>
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
<!-- Weather Modal -->
<div id="weatherModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="card">
      <div class="current-date" style="margin-bottom: 10px; font-size: 16px; color: #555;"></div>
      <div class="search">
        <input type="text" id="cityInput" placeholder="Enter city name" spellcheck="false">
        <input type="date" id="dateInput" style="margin-left: 10px;">
        <button id="searchBtn"><img src="./weather-app-img/images/search.png"></button>
      </div>
      <div class="error">
        <p>Invalid City Name or Date</p>
      </div>
      <div class="weather">
        <img src="./weather-app-img/images/clear.png" class="weather-icon">
        <h1 class="temp">22°C</h1>
        <h2 class="city">Sydney</h2>
        <div class="detail">
          <div class="col">
            <img src="./weather-app-img/images/humidity.png">
            <div>
              <p class="humidity">15%</p>
              <p>Humidity</p>
            </div>
          </div>
          <div class="col">
            <img src="./weather-app-img/images/wind.png">
            <div>
              <p class="speed">10km/h</p>
              <p>Wind Speed</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <script src="validAddCovoiturage.js"></script>
  <script src="validEditCovoiturage.js"></script>
  <script src="validDeleteCovoiturage.js"></script>
  <script src="meteo.js"></script>
</body>

</html>