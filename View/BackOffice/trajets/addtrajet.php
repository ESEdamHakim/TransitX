<?php 
include("../../../Controller/trajetcontroller.php");

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset(
        $_POST['place_depart'],
        $_POST['place_arrivee'],
        $_POST['heure_depart'],
        $_POST['duree'],
        $_POST['distance_km'],
        $_POST['prix']
    )
) {
    $place_depart = $_POST['place_depart'];
    $place_arrivee = $_POST['place_arrivee'];
    $heure_depart = $_POST['heure_depart'];
    $duree = $_POST['duree'];
    $distance_km = $_POST['distance_km'];
    $prix = $_POST['prix'];

    $trajet = new Trajet(
        $place_depart,
        $place_arrivee,
        $heure_depart,
        $duree,
        $distance_km,
        $prix
    );

    $controller = new TrajetController();
    $controller->addTrajet($trajet);

    header("Location: crud.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TransitX - Ajouter un Trajet</title>
  <!-- Styles -->
  <link rel="stylesheet" href="../../assets/css/main.css" />
  <link rel="stylesheet" href="assets/css/crud.css" />
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />
<!-- Custom Styles -->
<style>
    .section-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .section-header h2 {
      margin-top: 0.5rem;
      text-align: center;
      margin-bottom: 2rem;
    }

    .form-actions {
      margin-top: 1.5rem;
    }

    .form-group select {
      text-align: left;
      direction: ltr;
    }
  </style>
  <body>
<div class="dashboard">
  <aside class="sidebar">
  <div class="sidebar-header">
      <div class="logo">
        <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
        <span>Transit</span><span class="highlight">X</span>
      </div>
      <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    </div>

    <div class="sidebar-content">
      <nav class="sidebar-menu">
        <ul>
          <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
          <li><a href="../users/crud.php"><i class="fas fa-users"></i><span>Utilisateurs</span></a></li>
          <li class="active"><a href="crud.php"><i class="fas fa-bus"></i><span>Bus</span></a></li>
          <li><a href="../colis/crud.php"><i class="fas fa-box"></i><span>Colis</span></a></li>
          <li><a href="../reclamations/crud.php"><i class="fas fa-exclamation-circle"></i><span>Réclamations</span></a></li>
          <li><a href="../covoiturage/crud.php"><i class="fas fa-car-side"></i><span>Covoiturage</span></a></li>
          <li><a href="../blog/crud.php"><i class="fas fa-blog"></i><span>Blog</span></a></li>
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
        <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
      </a>
    </div>
  </aside>

  <main class="main-content">
    <section class="bus-form-section">
      <div class="container">
        <div class="section-header">
          <h2>Ajouter un Trajet
          <p>Remplissez le formulaire ci-dessous pour enregistrer un nouveau trajet.</p></h2>
        </div>
        <div class="bus-form-container">
          <form class="bus-form" method="POST">
            <div class="form-grid">
              <!-- Left Column -->
              <div class="form-column">
                <div class="form-group">
                  <label for="place_depart">Lieu de Départ:</label>
                  <input type="text" name="place_depart" id="place_depart">
                </div>

                <div class="form-group">
                  <label for="heure_depart">Heure de Départ:</label>
                  <input type="time" name="heure_depart" id="heure_depart">
                </div>

                <div class="form-group">
                  <label for="distance_km">Distance (en km):</label>
                  <input type="number" step="0.1" name="distance_km" id="distance_km">
                </div>
              </div>

              <!-- Right Column -->
              <div class="form-column">
                <div class="form-group">
                  <label for="place_arrivee">Lieu d’Arrivée:</label>
                  <input type="text" name="place_arrivee" id="place_arrivee">
                </div>

                <div class="form-group">
                  <label for="duree">Durée:</label>
                  <input type="text" name="duree" id="duree" placeholder="Ex: 2h30">
                </div>

                <div class="form-group">
                  <label for="prix">Prix (TND):</label>
                  <input type="number" step="0.1" name="prix" id="prix">
                </div>
              </div>
            </div>

            <div class="form-actions text-center">
            <a href="crud.php" class="btn cancel" style="margin-left: 10px;">
                  Annuler <i class="fas fa-times"></i>
                </a>
              <button type="submit" class="btn btn-primary">
                Ajouter le Trajet <i class="fas fa-plus"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>
</div>
<script src="assets/js/trajetvalidation.js"></script>
</body>
</html>

