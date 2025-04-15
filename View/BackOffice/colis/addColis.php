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
      header("Location: crud.php");
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
  <title>TransitX - Ajouter un Colis</title>

  <!-- CSS Imports -->
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/colis.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    .status {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .status.pending { background: #fff3cd; color: #856404; }
    .status.in-transit { background: #cce5ff; color: #004085; }
    .status.delivered { background: #d4edda; color: #155724; }
    .status.cancelled { background: #f8d7da; color: #721c24; }

    .dashboard-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-box {
      background: #fff;
      border-radius: 8px;
      padding: 1.25rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-box .stat-title { font-size: 0.9rem; color: #6c757d; margin-bottom: 0.5rem; }
    .stat-box .stat-value { font-size: 1.75rem; font-weight: 600; }
    .stat-box .stat-icon { align-self: flex-end; margin-top: -2.5rem; font-size: 1.5rem; opacity: 0.2; }

    .stat-box.primary { border-left: 4px solid #1f4f65; }
    .stat-box.success { border-left: 4px solid #28a745; }
    .stat-box.warning { border-left: 4px solid #ffc107; }
    .stat-box.danger { border-left: 4px solid #dc3545; }

    .colis-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      background-color: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
    }

    .filter-item label { font-weight: 500; font-size: 0.9rem; }
    .filter-item input, .filter-item select {
      padding: 0.5rem;
      border: 1px solid #ced4da;
      border-radius: 4px;
    }

    .parcels-table th, .parcels-table td {
      padding: 0.75rem 1rem;
    }

    .parcels-table th {
      background-color: #f8f9fa;
      font-weight: 600;
    }

    .parcels-table tr:hover {
      background-color: #f8f9fa;
    }

    .action-btn {
      width: 32px;
      height: 32px;
    }
  </style>
</head>

<body style="margin: 0; padding: 0;">
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
          <span>Transit</span><span class="highlight">X</span>
        </div>
        <button class="sidebar-toggle">
          <i class="fas fa-bars"></i>
        </button>
      </div>
      
      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li>
              <a href="../index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="../users/crud.php">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
              </a>
            </li>
            <li>
              <a href="../bus/crud.php">
                <i class="fas fa-bus"></i>
                <span>Bus</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
                <i class="fas fa-box"></i>
                <span>Colis</span>
              </a>
            </li>
            <li>
              <a href="../reclamations/crud.php">
                <i class="fas fa-exclamation-circle"></i>
                <span>Réclamations</span>
              </a>
            </li>
            <li>
              <a href="../covoiturage/crud.php">
                <i class="fas fa-car-side"></i>
                <span>Covoiturage</span>
              </a>
            </li>
            <li>
              <a href="../blog/crud.php">
                <i class="fas fa-blog"></i>
                <span>Blog</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
      
      <div class="sidebar-footer">
        <a href="#" class="user-profile">
          <img src="../assets/images/placeholder-admin.png" alt="Admin" class="user-img">
          <div class="user-info">
            <h4>Admin User</h4>
            <p>Administrateur</p>
          </div>
        </a>
        <a href="../../../index.php" class="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </a>
      </div>
    </aside>

    <main class="main-content">
      <section>
        <div class="container">
          <div class="header-left">
            <h2>Ajouter un Colis</h2>
            <p>Remplissez le formulaire ci-dessous</p>
          </div>
          <div class="colis-form-container">
            <form class="colis-form" method="POST">
              <div class="form-group">
                <label for="id_client">ID Client:</label>
                <input type="number" name="id_client" id="id_client" placeholder="Entrez l'ID du client">
              </div>

              <input type="hidden" name="id_covoit" id="id_covoit" value="">

              <div class="form-group">
                <label for="date_colis">Date d'envoi:</label>
                <input type="date" name="date_colis" id="date_colis">
              </div>

              <div class="form-group">
                <label for="statut">Statut:</label>
                <select name="statut" id="statut" style="border: 1px solid #dddddd; border-radius: 5px; padding: 8px;">
                  <option value="en attente" selected>En attente</option>
                  <option value="en transit">En transit</option>
                  <option value="livré">Livré</option>
                </select>
              </div>

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

                <div class="form-group">
                <label for="poids">Poids (kg)</label>
                <input type="number" name="poids" id="poids" placeholder="Poids" step="0.1">
                </div>
              </div>

              <input type="hidden" name="latitude_ram" id="latitude_ram">
              <input type="hidden" name="longitude_ram" id="longitude_ram">
              <input type="hidden" name="latitude_dest" id="latitude_dest">
              <input type="hidden" name="longitude_dest" id="longitude_dest">
              <input type="hidden" name="prix" id="prix">
              </br>
              <div class="form-actions text-center">
              <a href="crud.php" class="btn secondary">
    Annuler
    <i class="fas fa-times"></i>
  </a>
    <button type="submit" class="btn primary">
      Ajouter Colis
      <i class="fas fa-plus"></i>
    </button>   
  </div>
            </form>

            <div class="map-container">
              <h3>Localisation</h3>
              <div id="gmap_canvas" style="height: 400px; width: 100%;"></div>
              <div class="map-info">
                <p>
                  <i class="fas fa-info-circle"></i>
                  <strong>Instructions:</strong><br>
                  <strong>1:</strong> Cliquez sur la carte pour l'adresse de <strong>ramassage</strong><br>
                  <strong>2:</strong> Cliquez encore pour l'adresse de <strong>livraison</strong>.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
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

<script>
    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });

    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Filter colis based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });
  </script>
  <script src="assets/js/colisValidation.js"></script>
  </body>
</html>
