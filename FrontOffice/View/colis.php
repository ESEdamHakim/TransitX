<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>TransitX | Livraison de Colis</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Livraison de colis avec TransitX - Planifiez, suivez et gérez vos colis facilement." />
  <meta name="author" content="TransitX Team" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Bootstrap & Custom CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/styles.css" />

  <!-- Style amélioré -->
  <style>
    body {
      background-color: #ffffff;
      font-family: 'Segoe UI', sans-serif;
      color: #1f4f65;
    }

    header {
  background-color: #97c3a2;
  padding: 15px 30px;
  display: flex;
  align-items: center;
  gap: 260px; /* space between logo and nav */
}

    .logo-text {
      font-size: 24px;
      font-weight: bold;
      color: #1f4f65;
      margin-left: 10px;
    }

    nav a {
        position: relative;
  font-size: 18px;
  font-weight: 500;
  color: #fff;
  margin-left: 30px;
  transition: 0.3s;
}


    nav a.active, nav a:hover {
      color: #f9d86d;
    }

    h3 {
      color: #1f4f65;
      margin-bottom: 15px;
    }

    .form-control {
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .btn-success {
      background-color: #97c3a2;
      border: none;
      color: white;
      font-weight: bold;
    }

    .btn-success:hover {
      background-color: #1f4f65;
    }

    .alert-success {
      background-color: #dff0d8;
      border-color: #c3e6cb;
      color: #3c763d;
    }

    .alert-danger {
      background-color: #f8d7da;
      border-color: #f5c6cb;
      color: #721c24;
    }

    #gmap_canvas {
      border: 2px solid #97c3a2;
      border-radius: 8px;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="header">
    <a class="logo d-flex align-items-center" href="index.php">
      <img src="assets/TransitXLogo.png" alt="TransitX Logo" height="50" />
      <span class="logo-text">TransitX</span>
    </a>
    <nav class="nav">
      <a href="index.php">Accueil</a>
      <a href="#">Bus</a>
      <a href="colis.php" class="active">Colis</a>
      <a href="#">Covoiturage</a>
      <a href="#">Blog</a>
      <a href="#">À propos</a>
      <a href="#">Contact</a>
    </nav>
  </header>

  
  <!-- Main Content -->
  <section id="content" class="py-5">
    <div class="container">
      <div class="row">
        <!-- Formulaire de Colis -->
        <div class="col-md-6 mb-5">
        <br>
          <h3>Envoyer un Colis</h3>
          <p>Veuillez remplir les informations ci-dessous pour planifier la livraison de votre colis.</p>

          <div class="alert alert-success d-none" id="colisSuccess">
            <strong>Succès !</strong> Votre colis a été planifié avec succès.
          </div>
          <div class="alert alert-danger d-none" id="colisError">
            <strong>Erreur !</strong> Veuillez vérifier vos informations.
          </div>

          <form id="colis-form" method="POST" action="handle_colis.php">
            <div class="form-group mb-3">
              <label for="id_client">Identifiant du Client*</label>
              <input type="text" class="form-control" id="id_client" name="id_client" required>
            </div>

            <div class="form-group mb-3">
              <label for="adresse_colis">Adresse du Colis*</label>
              <input type="text" class="form-control" id="adresse_colis" name="adresse_colis" required>
            </div>

            <div class="form-group mb-3">
              <label for="statut_colis">Statut du Colis*</label>
              <select class="form-control" id="statut_colis" name="statut_colis" required>
                <option value="En attente">En attente</option>
                <option value="En cours de livraison">En cours de livraison</option>
                <option value="Livré">Livré</option>
              </select>
            </div>

            <div class="form-group mb-3">
              <label for="date_colis">Date de Livraison*</label>
              <input type="date" class="form-control" id="date_colis" name="date_colis" required>
            </div>

            <div class="form-group mb-3">
              <label for="id_categorie">Identifiant de Catégorie*</label>
              <input type="text" class="form-control" id="id_categorie" name="id_categorie" required>
            </div>

            <div class="form-group mb-4">
              <label for="description">Description*</label>
              <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Envoyer</button>
          </form>
        </div>

        <!-- Google Map -->
        <div class="col-md-6">
        <br>
          <h3>Lieu de Ramassage</h3>
          <div id="gmap_canvas" style="width:100%; height:500px;"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Scroll Up Button -->
  <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>

  <!-- Scripts -->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/custom.js"></script>

  <!-- Google Maps -->
  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <script>
    function initMap() {
      var pickupPoint = { lat: 36.8065, lng: 10.1815 }; // Tunis
      var map = new google.maps.Map(document.getElementById("gmap_canvas"), {
        zoom: 14,
        center: pickupPoint
      });
      var marker = new google.maps.Marker({
        position: pickupPoint,
        map: map,
        title: "Point de Ramassage - TransitX"
      });
    }
    window.onload = initMap;
  </script>
</body>
</html>
