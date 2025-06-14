<?php
require_once '../../../Controller/ColisController.php';

$ColisC = new ColisController();
$clients = $ColisC->getAllClients();

// Check if an ID is provided in the URL
if (!isset($_GET['id_colis'])) {
  die("Invalid Request");
}

$id_colis = $_GET['id_colis'];
$colis = null;

// Fetch the existing colis details
$list = $ColisC->listColis();
foreach ($list as $c) {
  if ($c['id_colis'] == $id_colis) {
    $colis = $c;
    break;
  }
}

if (!$colis) {
  die("Colis not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_covoit = null;

  $ColisC->updateColis(
    $id_colis,
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

  header("Location: ColisCovoitList.php?id_colis=$id_colis");
  exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Modifier un Colis</title>

  <!-- css Imports -->
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/colis.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

</head>

<body style="margin: 0; padding: 0;">
  <div class="dashboard">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">
      <section>
        <div class="container">
          <div class="section-header">
            <h2>Modifier un Colis
              <p>Modifiez les informations ci-dessous</p>
            </h2>
          </div>

          <div class="colis-form-container">
            <form class="colis-form" method="POST">
              <div class="form-group">
                <label for="id_client">Client :</label>
                <select name="id_client" id="id_client"
                  style="border: 1px solid #dddddd; border-radius: 5px; padding: 8px;">
                  <option value="">-- Sélectionner un client --</option>
                  <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>" <?php if ($colis['id_client'] == $client['id'])
                        echo 'selected'; ?>>
                      <?= htmlspecialchars($client['nom']) ?>   <?= htmlspecialchars($client['prenom']) ?> (ID:
                      <?= $client['id'] ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Hidden covoit ID field -->
              <input type="hidden" name="id_covoit" id="id_covoit"
                value="<?php echo htmlspecialchars($colis['id_covoit']); ?>">

              <div class="form-group">
                <label for="date_colis">Date d'envoi:</label>
                <input type="date" name="date_colis" id="date_colis"
                  value="<?php echo htmlspecialchars($colis['date_colis']); ?>">
              </div>

              <div class="form-group">
                <label for="statut">Statut:</label>
                <select name="statut" id="statut" style="border: 1px solid #dddddd; border-radius: 5px; padding: 8px;">
                  <option value="en attente" <?php if ($colis['statut'] == 'en attente')
                    echo 'selected'; ?>>En attente
                  </option>
                  <option value="en transit" <?php if ($colis['statut'] == 'en transit')
                    echo 'selected'; ?>>En transit
                  </option>
                  <option value="livré" <?php if ($colis['statut'] == 'livré')
                    echo 'selected'; ?>>Livré</option>
                </select>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="dimensions">Dimensions (cm)</label>
                  <div class="dimensions-inputs">
                    <input type="number" name="longueur" id="longueur" placeholder="L" step="1"
                      value="<?php echo htmlspecialchars($colis['longueur']); ?>">
                    <span>×</span>
                    <input type="number" name="largeur" id="largeur" placeholder="l" step="1"
                      value="<?php echo htmlspecialchars($colis['largeur']); ?>">
                    <span>×</span>
                    <input type="number" name="hauteur" id="hauteur" placeholder="H" step="1"
                      value="<?php echo htmlspecialchars($colis['hauteur']); ?>">
                  </div>
                  <!-- Place to show error for dimensions -->
                  <div id="dimensions-error" class="error-message-container"></div>
                </div>

                <div class="form-group">
                  <label for="poids">Poids (kg)</label>
                  <input type="number" name="poids" id="poids" placeholder="Poids" step="0.1"
                    value="<?php echo htmlspecialchars($colis['poids']); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="autocomplete-pickup">Adresse de ramassage</label>
                  <input id="autocomplete-pickup" type="text"
                    value="<?php echo htmlspecialchars($colis['lieu_ram']); ?>" autocomplete="off"
                    style="width:100%;margin-bottom:8px;">
                </div>
                <div class="form-group">
                  <label for="autocomplete-delivery">Adresse de livraison</label>
                  <input id="autocomplete-delivery" type="text"
                    value="<?php echo htmlspecialchars($colis['lieu_dest']); ?>" autocomplete="off"
                    style="width:100%;margin-bottom:8px;">
                </div>
              </div>


              <!-- Hidden fields for map coordinates and price -->
              <input type="hidden" name="lieu_ram" id="lieu_ram"
                value="<?php echo htmlspecialchars($colis['lieu_ram']); ?>">
              <input type="hidden" name="lieu_dest" id="lieu_dest"
                value="<?php echo htmlspecialchars($colis['lieu_dest']); ?>">
              <input type="hidden" name="latitude_ram" id="latitude_ram"
                value="<?php echo htmlspecialchars($colis['latitude_ram']); ?>">
              <input type="hidden" name="longitude_ram" id="longitude_ram"
                value="<?php echo htmlspecialchars($colis['longitude_ram']); ?>">
              <input type="hidden" name="latitude_dest" id="latitude_dest"
                value="<?php echo htmlspecialchars($colis['latitude_dest']); ?>">
              <input type="hidden" name="longitude_dest" id="longitude_dest"
                value="<?php echo htmlspecialchars($colis['longitude_dest']); ?>">
              <input type="hidden" name="prix" id="prix" value="<?php echo htmlspecialchars($colis['prix']); ?>">

              </br>
              <div class="form-actions text-center">
                <a href="crud.php" class="btn btn-outline">
                  Annuler
                  <i class="fas fa-times"></i>
                </a>
                <button type="submit" class="btn btn-primary" style="margin-left:10px;">
                  Mettre à jour
                  <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" id="reset-map" class="btn btn-primary" style="margin-left:10px;">
                  Réinitialiser la carte
                  <i class="fas fa-undo"></i>
                </button>
              </div>
            </form>

            <div class="map-container">
              <h3>Localisation</h3>
              <div id="gmap_canvas" style="height: 400px; min-width: 400px;">
                <!-- La carte Google Maps s'affichera ici -->
              </div>
              <div class="map-info"
                style="background-color: #f9f9f9; border-radius: 6px; padding: 8px 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 400px; margin: 5px auto;">
                <p style="font-size: 14px; color: #333; line-height: 1.3; margin: 0;">
                  <i class="fas fa-info-circle" style="color: #86b391; margin-right: 6px;"></i>
                  <span style="font-weight: 600; color: #555;">Instructions:</span>
                  <br>
                  <span>
                    <strong>1:</strong> Utilisez la recherche ou cliquez sur la carte pour l'adresse de
                    <strong>ramassage</strong><br>
                    <strong>2:</strong> Utilisez la recherche ou cliquez encore pour l'adresse de
                    <strong>livraison</strong>.
                  </span>
                </p>
              </div>
              <!-- Route info -->
              <div id="route-info" class="route-info"
                style="display: none; margin: 10px auto; padding: 10px; border-radius: 6px; background-color: #eaf4ed; max-width: 400px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); font-size: 14px;">
                <p style="margin: 0;"><strong> Temps estimé:</strong> <span id="estimated-time"></span></p>
                <p style="margin: 0;"><strong> Distance estimée:</strong> <span id="estimated-distance"></span></p>
              </div>
              <div id="map-warning" class="map-warning" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="assets/js/colisValidation.js"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRqtevb1ZGEqlL_tceScv_8nI-XccCsrI&libraries=places&callback=initMap"
    async defer></script>


  <script>
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
  <script src="assets/js/colisValidation.js"></script>
</body>

</html>