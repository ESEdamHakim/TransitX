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
        <?php include 'displaycovoiturage.php'; ?>
        </div>
      </div>
    </section>

    <form class="create-ride-form" action="addCovoiturage.php" method="POST">
  <div class="form-row">
    <div class="form-group">
      <label for="start-point">Point de départ</label>
      <input type="text" id="start-point" name="lieu_depart" placeholder="Ville de départ" required>
    </div>
    <div class="form-group">
      <label for="end-point">Destination</label>
      <input type="text" id="end-point" name="lieu_arrivee" placeholder="Ville d'arrivée" required>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="ride-date">Date</label>
      <input type="date" id="ride-date" name="date_depart" required>
    </div>
    <div class="form-group">
      <label for="ride-time">Heure</label>
      <input type="time" id="ride-time" name="temps_depart" required>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="seats">Places disponibles</label>
      <select id="seats" name="places_dispo" required>
        <option value="1">1 place</option>
        <option value="2">2 places</option>
        <option value="3">3 places</option>
        <option value="4">4 places</option>
      </select>
    </div>
    <div class="form-group">
      <label for="price-per-seat">Prix par place (TND)</label>
      <input type="number" id="price-per-seat" name="prix" min="1" step="1" required>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="accept-parcels">Accepte les colis</label>
      <select id="accept-parcels" name="accepte_colis" required>
        <option value="oui">Oui</option>
        <option value="non">Non</option>
      </select>
    </div>
    <div class="form-group">
      <label for="full-parcels">Colis complet</label>
      <select id="full-parcels" name="colis_complet" required>
        <option value="oui">Oui</option>
        <option value="non">Non</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="ride-details">Détails supplémentaires</label>
    <select id="details-options" class="form-control" onchange="updateDetailsInput()">
    <option value="">Sélectionnez un détail</option>
    <option value="Bagages légers uniquement.">✅ Bagages légers uniquement.</option>
    <option value="Trajet non-fumeur.">✅ Trajet non-fumeur.</option>
    <option value="Merci d’être ponctuel.">✅ Merci d’être ponctuel.</option>
    <option value="Pas de retard accepté.">✅ Pas de retard accepté.</option>
    <option value="custom">Autre (ajoutez votre propre détail)</option>
  </select>
    <textarea id="ride-details" name="details" rows="3" maxlength="100" placeholder="Précisez les détails de votre trajet (arrêts, bagages autorisés, etc.)"></textarea>
    <span id="details-error" style="color: red; font-size: 0.9em;"></span>
  </div>
  <button type="submit" class="btn btn-primary">
    Publier le trajet
    <i class="fas fa-paper-plane"></i>
  </button>
</form>

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

  // Form validation and submission
  document.addEventListener("DOMContentLoaded", () => {
  const createRideForm = document.querySelector(".create-ride-form");

  createRideForm.addEventListener("submit", function (e) {
    // Get form fields
    const lieuDepart = document.getElementById("start-point").value.trim();
    const lieuArrivee = document.getElementById("end-point").value.trim();
    const dateDepart = document.getElementById("ride-date").value.trim();
    const tempsDepart = document.getElementById("ride-time").value.trim();
    const placesDispo = parseInt(document.getElementById("seats").value);
    const prix = parseFloat(document.getElementById("price-per-seat").value);
    const accepteColis = document.getElementById("accept-parcels").value.trim();
    const colisComplet = document.getElementById("full-parcels").value.trim();
    const details = document.getElementById("ride-details").value.trim();

    // Validate required fields
    if (!lieuDepart || !lieuArrivee || !dateDepart || !tempsDepart || !details) {
      alert("Veuillez remplir tous les champs obligatoires.");
      e.preventDefault();
      return;
    }

    // Validate numeric fields
    if (isNaN(placesDispo) || placesDispo <= 0) {
      alert("Le nombre de places disponibles doit être supérieur à zéro.");
      e.preventDefault();
      return;
    }

    if (isNaN(prix) || prix <= 0) {
      alert("Le prix par place doit être supérieur à zéro.");
      e.preventDefault();
      return;
    }

    // Validate select fields
    if (!accepteColis) {
      alert("Veuillez indiquer si vous acceptez les colis.");
      e.preventDefault();
      return;
    }

    if (!colisComplet) {
      alert("Veuillez indiquer si les colis sont complets.");
      e.preventDefault();
      return;
    }

    // If all validations pass, the form will be submitted
  });
});
</script>
</body>
</html>
