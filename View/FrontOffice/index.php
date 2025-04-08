<!DOCTYPE html>
<html lang="en">
  <!-- Created by Tivotal -->
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TransitX</title>

    <!-- Font Awesome (for icons) -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />

    <!-- CSS file -->
    <link rel="stylesheet" href="assets/styles.css" />
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

    <section class="home">
      <div class="video-wrapper">
        <video class="active" src="media/vid1.mp4" autoplay muted loop></video>
        <video src="media/vid2.mp4" autoplay muted loop></video>
        <video src="media/vid3.mp4" autoplay muted loop></video>
      </div>

      <div class="content">
        <h1>Move Green <br /><span>Live Glean</span></h1>
        <p>
        TransitX est une application web destinée à améliorer la mobilité urbaine grâce au covoiturage, à la gestion des bus, et à la livraison intelligente. Elle vise à promouvoir des déplacements plus écologiques, efficaces et durables.
        </p>
        <a href="#" class="btn-primary">Read more</a>
      </div>

      <div class="icons">
        <a href="#" aria-label="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#" aria-label="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#" aria-label="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
      </div>

      <div class="video-nav">
        <div class="nav-item active"></div>
        <div class="nav-item"></div>
        <div class="nav-item"></div>
      </div>
    </section>

    <script src="app.js"></script>
  </body>
</html>
