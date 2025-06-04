<?php
require_once __DIR__ . '/../../../Controller/vehiculeC.php';
require_once __DIR__ . '/../../../appConfig.php';


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
  <link rel="stylesheet" href="../colis/assets/css/main.css">
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
          <li class="active"><a href="../colis/index.php">Colis</a></li>
          <li><a href="index.php">Covoiturage</a></li>
          <li><a href="../blog/index.php">Blog</a></li>
          <li><a href="../reclamation/index.php">Réclamation</a></li>
          <li><a href="../vehicule/index.php">Véhicule</a></li>
        </ul>
      </nav>
      <div class="header-right">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
          <a href="../../BackOffice/index.php" class="btn btn-outline">Dashboard</a>
        <?php endif; ?>
        <a href="../../../index.php" class="btn btn-primary">Déconnexion</a>
      </div>
    </div>
  </header>

  <main>

    <section class="popular-routes">
      <div class="container">
        <div class="section-header">
          <span class="badge">Covoiturages</span>
          <h2>Choisir un covoiturage</h2>
        </div>
        <div class="route-cards">
          <?php include 'ColisCovoitDisplay.php'; ?>
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

  <?php include '../../assets/footer.php'; ?>
  
</body>

</html>