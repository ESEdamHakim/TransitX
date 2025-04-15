<?php
require_once '../../../Controller/ColisController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (
      isset($_POST['id_client'], $_POST['statut'], $_POST['date_colis'],
      $_POST['longueur'], $_POST['largeur'], $_POST['hauteur'], $_POST['poids'],
      $_POST['latitude_ram'], $_POST['longitude_ram'], $_POST['latitude_dest'], $_POST['longitude_dest'],
      $_POST['prix'])
  ) {
      $id_covoit = !empty($_POST['id_covoit']) ? $_POST['id_covoit'] : NULL;

      $ColisC = new ColisController();
      $ColisC->addColis(
          $_POST['id_client'],
          $id_covoit,
          $_POST['statut'],
          $_POST['date_colis'],
          $_POST['longueur'],
          $_POST['largeur'],
          $_POST['hauteur'],
          $_POST['poids'],
          $_POST['latitude_ram'],
          $_POST['longitude_ram'],
          $_POST['latitude_dest'],
          $_POST['longitude_dest'],
          $_POST['prix']
      );
      header("Location: ColisList.php");
      exit();
  } else {
      echo "Erreur : tous les champs obligatoires ne sont pas remplis.";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Service de Colis</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/colis.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <style>
    .section-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .section-header h2 {
      margin-top: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .tracking-form {
      display: flex;
      justify-content: center;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }
    
    .feature-card {
      text-align: center;
      padding: 2rem;
      border-radius: 8px;
      background-color: #f9f9f9;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
    }
    
    .pricing-table-container {
      display: flex;
      justify-content: center;
      margin-top: 2rem;
    }
    
    .faq-container {
      max-width: 800px;
      margin: 0 auto;
    }
  </style>
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
          <li class="active"><a href="index.php">Colis</a></li>
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
    <section class="colis-hero">
      <div class="hero-content">
        <h1>Service de Livraison de Colis</h1>
        <p>Envoyez vos colis de manière écologique et économique partout en Tunisie.</p>
        <div style="text-align: center;">
  <a href="ColisList.php" class="btn btn-primary">Mes Colis</a>
</div>
      </div>
    </section>

    <section class="colis-form-section">
      <div class="container">
        <div class="section-header">
          <span class="badge">Expédition</span>
          <h2>Envoyer un Colis</h2>
          <p>Remplissez le formulaire ci-dessous pour calculer le prix de votre envoi.</p>
        </div>

        <script src="assets/js/colisValidation.js"></script>
        <div class="colis-form-container">
  <form class="colis-form" method="POST">
  <input type="hidden" name="id_client" id="id_client" value="3">

  <input type="hidden" name="id_covoit" id="id_covoit" value="">
  <br>
  

  <div class="form-group">
    <label for="date_colis">Date d'envoi</label>
    <input type="date" name="date_colis" id="date_colis">
  </div>
  <br>
  <input type="hidden" name="statut" id="statut" value="en attente">

  <div class="form-row">
  <div class="form-group">
    <label for="dimensions">Dimensions (cm)</label>
    <div class="dimensions-inputs">
      <input type="number" name="longueur" id="longueur" placeholder="L" step="1">
      <span>×</span>
      <input type="number" name="largeur" id="largeur" placeholder="l" step="1">
      <span>×</span>
      <input type="number" name="hauteur" id="hauteur" placeholder="H" step="1">
    </div>
  </div>
  <br>
  <div class="form-group">
    <label for="poids">Poids (kg)</label>
    <input type="number" name="poids" id="poids" placeholder="Poids" step="0.1">
  </div>
  
  <br>
</div>

<br>
<input type="hidden" name="latitude_ram" id="latitude_ram">
<input type="hidden" name="longitude_ram" id="longitude_ram">
<input type="hidden" name="latitude_dest" id="latitude_dest">
<input type="hidden" name="longitude_dest" id="longitude_dest">
<input type="hidden" name="prix" id="prix"> <!-- You can calculate this via JS later -->
<br>
  <div class="form-actions text-center">
    <button type="submit" class="btn btn-primary">
      Calculer le prix
      <i class="fas fa-calculator"></i>
    </button>
  </div>
</form>


<div class="map-container">
  <h3>Localisation</h3>
  <div id="gmap_canvas" style="height: 400px; width: 400px;">
    <!-- La carte Google Maps s'affichera ici -->
  </div>
<div class="map-info" style="background-color: #f9f9f9; border-radius: 6px; padding: 8px 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 400px; margin: 5px auto;">
  <p style="font-size: 14px; color: #333; line-height: 1.3; margin: 0;">
    <i class="fas fa-info-circle" style="color: #86b391; margin-right: 6px;"></i>
    <span style="font-weight: 600; color: #555;">Instructions:</span> 
    <br>
    <span>
      <strong>1:</strong> Cliquez sur la carte pour l'adresse de <strong>ramassage</strong><br>
      <strong>2:</strong> Cliquez encore pour l'adresse de <strong>livraison</strong>.
    </span>
  </p>
</div>



  <!-- Replace with your actual API key -->
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
  let map;
  let pickupMarker = null;
  let deliveryMarker = null;
  let currentLocationMarker = null; // Marker for current location
  let clickStep = 0;

  function initMap() {
    const defaultLocation = { lat: 36.8980431, lng: 10.1888733 }; // Default location (Tunis)

    // Initialize map with default location first
    map = new google.maps.Map(document.getElementById("gmap_canvas"), {
      center: defaultLocation,
      zoom: 13,
    });

    // Add marker for the default location
    new google.maps.Marker({
      position: defaultLocation,
      map: map,
      title: "Default Location",
    });

    // Handle map clicks for setting pickup and delivery locations
    map.addListener("click", function (event) {
      const clickedLocation = event.latLng;

      if (clickStep === 0) {
        if (pickupMarker) pickupMarker.setMap(null); // Remove old pickup marker
        pickupMarker = new google.maps.Marker({
          position: clickedLocation,
          map: map,
          label: "A", // Pickup
        });

        document.getElementById("latitude_ram").value = clickedLocation.lat();
        document.getElementById("longitude_ram").value = clickedLocation.lng();
        clickStep = 1;
        alert("Pickup location set. Now click to choose the delivery location.");
      } else if (clickStep === 1) {
        if (deliveryMarker) deliveryMarker.setMap(null); // Remove old delivery marker
        deliveryMarker = new google.maps.Marker({
          position: clickedLocation,
          map: map,
          label: "B", // Delivery
        });

        document.getElementById("latitude_dest").value = clickedLocation.lat();
        document.getElementById("longitude_dest").value = clickedLocation.lng();
        clickStep = 0;
        alert("Delivery location set.");
      }
    });
  }

  window.onload = initMap;
</script>


    </div>
        </div>
      </div>
    </section>

    <section class="colis-pricing">
      <div class="container">
        <div class="section-header">
          <span class="badge">Tarifs</span>
          <h2>Nos Tarifs</h2>
          <p>Des tarifs transparents et compétitifs pour tous vos envois.</p>
        </div>
        <div class="pricing-table-container">
          <table class="pricing-table">
            <thead>
              <tr>
                <th>Poids</th>
                <th>Distance < 10km</th>
                <th>Distance 10-30km</th>
                <th>Distance > 30km</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>< 1kg</td>
                <td>5 TND</td>
                <td>8 TND</td>
                <td>12 TND</td>
              </tr>
              <tr>
                <td>1-5kg</td>
                <td>8 TND</td>
                <td>12 TND</td>
                <td>18 TND</td>
              </tr>
              <tr>
                <td>5-10kg</td>
                <td>12 TND</td>
                <td>18 TND</td>
                <td>25 TND</td>
              </tr>
              <tr>
                <td>> 10kg</td>
                <td>15 TND</td>
                <td>22 TND</td>
                <td>30 TND</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="pricing-note text-center">* Des frais supplémentaires peuvent s'appliquer pour les colis volumineux ou nécessitant une manipulation spéciale.</p>
      </div>
    </section>
    
    <section class="colis-features">
      <div class="container">
        <div class="section-header">
          <span class="badge">Avantages</span>
          <h2>Pourquoi Choisir Notre Service de Colis</h2>
          <p>Découvrez les avantages qui font de notre service de livraison le choix idéal pour vos envois.</p>
        </div>
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-leaf"></i>
            </div>
            <h3>Écologique</h3>
            <p>Nos livraisons sont effectuées avec des véhicules à faible émission pour réduire l'impact environnemental.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-truck"></i>
            </div>
            <h3>Rapide</h3>
            <p>Livraison le jour même ou le lendemain selon la distance et la disponibilité.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Sécurisé</h3>
            <p>Vos colis sont assurés et manipulés avec soin tout au long du trajet.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3>Économique</h3>
            <p>Tarifs compétitifs et transparents, sans frais cachés.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="faq">
      <div class="container">
        <div class="section-header">
          <span class="badge">FAQ</span>
          <h2>Questions Fréquentes</h2>
          <p>Trouvez les réponses à vos questions sur notre service de livraison de colis.</p>
        </div>
        <div class="faq-container">
          <div class="faq-item">
            <div class="faq-question">
              <h3>Comment fonctionne le service de livraison de colis ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Vous remplissez le formulaire avec les détails de votre colis, nous calculons le prix, et après paiement, un de nos livreurs vient récupérer votre colis pour le livrer à destination.</p>
            </div>
          </div>
          <div class="faq-item">
            <div class="faq-question">
              <h3>Quels types de colis puis-je envoyer ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Vous pouvez envoyer la plupart des objets non dangereux et non périssables. Les objets de valeur doivent être déclarés et assurés séparément.</p>
            </div>
          </div>
          <div class="faq-item">
            <div class="faq-question">
              <h3>Comment suivre mon colis ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Après l'envoi, vous recevrez un numéro de suivi par email. Vous pourrez utiliser ce numéro sur notre site ou notre application pour suivre votre colis en temps réel.</p>
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
            <li><a href="../bus/index.php">Bus</a></li>
            <li><a href="../covoiturage/index.php">Covoiturage</a></li>
            <li><a href="index.php">Colis</a></li>
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
</body>
</html>
