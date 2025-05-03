<?php
require_once '../../../Controller/ColisController.php';

$ColisC = new ColisController();
$clients = $ColisC->getAllClients();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (
    isset(
    $_POST['id_client'],
    $_POST['statut'],
    $_POST['date_colis'],
    $_POST['longueur'],
    $_POST['largeur'],
    $_POST['hauteur'],
    $_POST['poids'],
    $_POST['lieu_ram'],
    $_POST['lieu_dest'],
    $_POST['latitude_ram'],
    $_POST['longitude_ram'],
    $_POST['latitude_dest'],
    $_POST['longitude_dest'],
    $_POST['prix']
  )
  ) {
    $id_covoit = !empty($_POST['id_covoit']) ? $_POST['id_covoit'] : NULL;

    $ColisC->addColis(
      $_POST['id_client'],
      $id_covoit,
      $_POST['statut'],
      $_POST['date_colis'],
      $_POST['longueur'],
      $_POST['largeur'],
      $_POST['hauteur'],
      $_POST['poids'],
      $_POST['lieu_ram'],
      $_POST['lieu_dest'],
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

  <!-- css Imports -->
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/colis.css">
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

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
          <div class="section-header">
            <h1>Ajouter un Colis</h1>
            <p>Remplissez le formulaire ci-dessous</p>
          </div>
          <div class="colis-form-container">
            <form class="colis-form" method="POST">
              <div class="form-group">
                <label for="id_client">Client :</label>
                <select name="id_client" id="id_client"
                  style="border: 1px solid #dddddd; border-radius: 5px; padding: 8px;">
                  <option value="">-- Sélectionner un client --</option>
                  <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id_user'] ?>">
                      <?= $client['nom'] ?>   <?= $client['prenom'] ?> (ID: <?= $client['id_user'] ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
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
                  <!-- Place to show error for dimensions -->
                  <div id="dimensions-error" class="error-message-container"></div>
                </div>

                <div class="form-group">
                  <label for="poids">Poids (kg)</label>
                  <input type="number" name="poids" id="poids" placeholder="Poids" step="0.1">
                </div>
              </div>

              <input type="hidden" name="lieu_ram" id="lieu_ram">
              <input type="hidden" name="lieu_dest" id="lieu_dest">
              <input type="hidden" name="latitude_ram" id="latitude_ram">
              <input type="hidden" name="longitude_ram" id="longitude_ram">
              <input type="hidden" name="latitude_dest" id="latitude_dest">
              <input type="hidden" name="longitude_dest" id="longitude_dest">
              <input type="hidden" name="prix" id="prix">
              </br>
              <div class="form-actions text-center">
                <a href="crud.php" class="btn btn-outline">
                  Annuler
                  <i class="fas fa-times"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                  Ajouter Colis
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </form>

            <div class="map-container">
              <h3>Localisation</h3>
              <div id="gmap_canvas" style="height: 400px; width: 400px;"></div>
              <div class="map-info"
                style="background-color: #f9f9f9; border-radius: 6px; padding: 8px 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 400px; margin: 5px auto;">
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
              <div id="map-warning" class="map-warning" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="assets/js/colisValidation.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/somanchiu/Keyless-Google-Maps-API@v6.9/mapsJavaScriptAPI.js"></script>


  <script>
    // Sidebar Toggle
    document.querySelector('.sidebar-toggle').addEventListener('click', function () {
      document.querySelector('.sidebar').classList.toggle('collapsed');
      document.querySelector('.main-content').classList.toggle('expanded');
    });

    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        // Filter colis based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });
  </script>
</body>

</html>