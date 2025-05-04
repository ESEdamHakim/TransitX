<?php
include("../../../Controller/trajetcontroller.php");

$controller_trajet = new TrajetController();
$trajetlist = $controller_trajet->listTrajets();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Services de Bus</title>
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
          <li class="active"><a href="index.php">Bus</a></li>
          <li><a href="../colis/index.php">Colis</a></li>
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
    <section class="bus-hero">
      <div class="hero-content">
        <h1>Services de Bus</h1>
        <p>Découvrez nos itinéraires de bus écologiques pour vos déplacements quotidiens.</p>
      </div>
    </section>

    <section class="bus-search">
      <div class="container">
        <div class="section-header">
          <span class="badge">Recherche</span>
          <h2>Trouvez votre trajet</h2>
        </div>
        <div class="search-container">
          <form class="search-form" id="searchForm">
            <div class="form-group">
              <label for="arrival">Arrivée</label>
              <input type="text" id="arrival" placeholder="Place d'arrivée">
            </div>
            <div class="form-group">
              <label for="start-time">Heure de début</label>
              <input type="time" id="start-time" placeholder="HH:MM">
            </div>
            <div class="form-group">
              <label for="end-time">Heure de fin</label>
              <input type="time" id="end-time" placeholder="HH:MM">
            </div>
            <button type="submit" class="btn btn-primary">
              Rechercher
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
    </section>

    <section class="bus-routes">
      <div class="container">
        <div class="section-header">
          <span class="badge">Trajets</span>
          <h2>Les trajets</h2>
        </div>
        <div class="route-cards">
          <?php foreach ($trajetlist as $trajet): ?>
            <div class="route-card">
              <div class="route-info">
                <div class="route-cities">
                  <span class="departure"><?= htmlspecialchars($trajet['place_depart']) ?></span>
                  <i class="fas fa-long-arrow-alt-right"></i>
                  <span class="arrival"><?= htmlspecialchars($trajet['place_arrivee']) ?></span>
                </div>
                <div class="route-details">
                  <div class="detail">
                    <i class="fas fa-clock"></i>
                    <span><?= htmlspecialchars($trajet['heure_depart']) ?></span>
                  </div>
                  <div class="detail">
                    <i class="fas fa-hourglass-start"></i>
                    <span><?= htmlspecialchars($trajet['duree']) ?></span>
                  </div>
                  <div class="detail">
                    <i class="fas fa-road"></i>
                    <span><?= htmlspecialchars($trajet['distance_km']) ?> km</span>
                  </div>
                </div>
              </div>

              <div class="route-price">
                <span class="price"><?= htmlspecialchars($trajet['prix']) ?> TND</span>
                <button class="btn btn-primary toggle-info-btn" type="button" data-id="<?= $trajet['id_trajet'] ?>">
                  Informations sur les bus
                </button>

                <div id="bus-info-modal-<?= $trajet['id_trajet'] ?>" class="modal">
                  <div class="modal-content">
                    <span class="close-btn"
                      onclick="closeModal('bus-info-modal-<?= $trajet['id_trajet'] ?>')">&times;</span>
                    <div class="modal-header">
                      <h2>Informations sur les bus</h2>
                    </div>
                    <div class="modal-body">
                      <?php
                      $user_id = 1;
                      $buses = $controller_trajet->getBusesByTrajetId($trajet['id_trajet'], $user_id);
                      if (!empty($buses)) {
                        foreach ($buses as $bus): ?>
                          <div class="bus-info">
                            <p><strong>Statut:</strong> <?= htmlspecialchars($bus['statut']) ?></p>
                            <p><strong>Numéro de bus:</strong> <?= htmlspecialchars($bus['num_bus']) ?></p>
                            <p><strong>Capacité:</strong> <?= htmlspecialchars($bus['capacite']) ?> personnes</p>
                            <p><strong>Places disponibles:</strong>
                              <span class="nbplacesdispo" data-bus-id="<?= $bus['id_bus'] ?>">
                                <?= htmlspecialchars($bus['nbplacesdispo']) ?>
                              </span> personnes
                            </p>
                            <p><strong>Type de bus:</strong> <?= htmlspecialchars($bus['type_bus']) ?></p>
                            <p><strong>Marque:</strong> <?= htmlspecialchars($bus['marque']) ?></p>
                            <p><strong>Modèle:</strong> <?= htmlspecialchars($bus['modele']) ?></p>
                            <p><strong>Date de mise en service:</strong> <?= htmlspecialchars($bus['date_mise_en_service']) ?>
                            </p>
                            <?php if ($bus['reserved']): ?>
                              <button class="annuler-btn" data-bus-id="<?= $bus['id_bus'] ?>"
                                data-bus-num="<?= $bus['num_bus'] ?>">
                                Annuler la réservation
                              </button>
                            <?php else: ?>
                              <button class="reserver-btn" data-bus-id="<?= $bus['id_bus'] ?>"
                                data-bus-num="<?= $bus['num_bus'] ?>">
                                Réserver ce bus
                              </button>
                            <?php endif; ?>
                          </div>
                        <?php endforeach;
                      } else {
                        echo "<p>Aucun bus associé à ce trajet.</p>";
                      }
                      ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          <?php endforeach; ?>
          <!-- Success Modal -->
          <div id="successModal" class="modal">
            <div class="modal-content">
              <div class="modal-body">
                <div class="bus-info">
                  <span class="close-btn" onclick="closeModal('successModal')">&times;</span>
                  <h2 id="modalTitle">Réservation réussie !</h2>
                  <p id="successMessage"></p>
                </div>
              </div>
            </div>
          </div>
          <!-- Error Modal -->
          <div id="errorModal" class="modal">
            <div class="modal-content">
              <div class="modal-body">
                <div class="bus-info">
                  <span class="close-btn" onclick="closeModal('errorModal')">&times;</span>
                  <h2>Erreur</h2>
                  <p id="errorMessage"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bus-map">
      <div class="container">
        <div class="section-header">
          <span class="badge">Itinéraires</span>
          <h2>Nos Itinéraires</h2>
          <p>Consultez notre réseau de lignes de bus à travers le pays.</p>
        </div>
        <div class="map-container">
          <img src="../../assets/images/bus-map.jpg" alt="Bus Routes Map" class="route-map">
        </div>
      </div>
    </section>

    <section class="faq">
      <div class="container">
        <div class="section-header">
          <span class="badge">FAQ</span>
          <h2>Questions Fréquentes</h2>
          <p>Trouvez les réponses à vos questions sur nos services de bus.</p>
        </div>
        <div class="faq-container">
          <div class="faq-item">
            <div class="faq-question">
              <h3>Comment réserver un billet de bus ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Vous pouvez réserver un billet de bus directement sur notre site web ou via notre application mobile.
                Sélectionnez votre trajet, choisissez la date et l'heure, puis procédez au paiement.</p>
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <h3>Puis-je annuler mon billet ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Oui, vous pouvez annuler votre billet jusqu'à 24 heures avant le départ. Des frais d'annulation peuvent
                s'appliquer selon les conditions de votre tarif.</p>
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <h3>Les animaux sont-ils autorisés à bord ?</h3>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Les petits animaux de compagnie sont autorisés s'ils sont transportés dans un sac ou une cage
                appropriée. Les chiens guides pour personnes malvoyantes sont toujours acceptés.</p>
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
            <li><a href="index.php">Bus</a></li>
            <li><a href="../covoiturage/index.php">Covoiturage</a></li>
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
  <script src="assets/js/main.js"></script>


</body>

</html>