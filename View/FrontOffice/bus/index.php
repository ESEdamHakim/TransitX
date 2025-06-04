<?php
include("../../../Controller/trajetcontroller.php");
include("../../../Controller/buscontroller.php");
session_start();
$controller_trajet = new TrajetController();
$trajetlist = $controller_trajet->listTrajets();
$favorisList = $controller_trajet->getFavorisByUserId($_SESSION['user_id']);

$controller = new BusController();
$notifications = $controller->getNotificationsForUser($_SESSION['user_id']);

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
  <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/card@2.5.4/lib/card.css">
  <script src="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.js"></script>
</head>

<body>
  <?php include '../../assets/chatbot/chatbot.php'; ?>
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
          <li><a href="../vehicule/index.php">Véhicule</a></li>
        </ul>
      </nav>
      <div class="header-right">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
          <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <?php endif; ?>
        <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
        <!-- Notification Button -->
        <div class="notification-container">
          <button id="notifBtn" class="notify-button">
            <i class="fa-regular fa-bell text-2xl" style="color: #86b391;"></i>
            <!-- Notification Badge -->
            <?php if (count($notifications) > 0): ?>
              <span
                class="notif-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full badge-pulse">
                <?= count($notifications) ?>
              </span>
            <?php endif; ?>
          </button>

          <!-- Notification Dropdown -->
          <div id="notifBox" class="notification-dropdown hidden">
            <div class="p-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
            </div>

            <div class="max-h-96 overflow-y-auto">
              <?php if (empty($notifications)): ?>
                <div class="p-4 text-center text-gray-500">Aucune notification</div>
              <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                  <div
                    class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 cursor-pointer"
                    data-id="<?= htmlspecialchars($notif['id']) ?>">
                    <div class="text-sm text-gray-800 notification-message">
                      <?php
                      // Format the message to enhance the emoji and styling
                      $message = htmlspecialchars($notif['message']);
                      // Trim any leading or trailing whitespace
                      $message = trim($message);
                      // Make the bus emoji larger
                      $message = preg_replace('/🚌/', '<span class="emoji">🚌</span>', $message, 1);
                      // Convert newlines to <br> tags
                      $message = nl2br($message);
                      // Make the first line (title) bold - improved regex to avoid extra spaces
                      $message = preg_replace('/^(.*?)(?:\n|$)/s', '<strong>$1</strong>', $message, 1);
                      // Output the formatted message
                      echo $message;
                      ?>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                      <?php
                      // Format the timestamp to be more readable
                      $timestamp = strtotime($notif['created_at']);
                      $now = time();
                      $diff = $now - $timestamp - 3600;

                      if ($diff < 60) {
                        echo "À l'instant";
                      } elseif ($diff < 3600) {
                        $minutes = floor($diff / 60);
                        echo "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
                      } elseif ($diff < 86400) {
                        $hours = floor($diff / 3600);
                        echo "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
                      } elseif ($diff < 604800) {
                        $days = floor($diff / 86400);
                        echo "Il y a " . $days . " jour" . ($days > 1 ? 's' : '');
                      } else {
                        echo date('d/m/Y à H:i', $timestamp);
                      }
                      ?>
                    </p>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <script>
          // Get DOM elements
          const notifBtn = document.getElementById("notifBtn");
          const notifBox = document.getElementById("notifBox");

          // Toggle notification dropdown
          notifBtn.addEventListener("click", function (event) {
            event.stopPropagation();

            // Toggle between hidden and visible
            notifBox.classList.toggle("hidden");
            notifBox.classList.toggle("show");
          });

          // Close dropdown when clicking outside
          document.addEventListener("click", function (event) {
            if (!notifBtn.contains(event.target) && !notifBox.contains(event.target)) {
              notifBox.classList.add("hidden");
              notifBox.classList.remove("show");
            }
          });

          // Add click handler for individual notifications
          document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function () {
              const notifId = this.getAttribute('data-id');
              console.log('Clicked notification:', notifId);

            });
          });
        </script>
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


    <?php if (!empty($favorisList)): ?>
      <section class="bus-routes">
        <div class="container">
          <div class="section-header">
            <span class="badge">Favoris</span>
            <h2>Mes trajets favoris</h2>
          </div>
          <div id="mes-favoris" class="route-cards">
            <?php foreach ($favorisList as $trajet): ?>
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
                    <div class="detail">
                      <button class="favoris-btn favorited" data-trajet-id="<?= $trajet['id_trajet'] ?>">
                        <i class="fas fa-heart"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="route-price">
                  <span class="price"><?= htmlspecialchars($trajet['prix']) ?> TND</span>
                  <button class="btn btn-primary toggle-info-btn" type="button" data-id="<?= $trajet['id_trajet'] ?>">
                    Informations sur les bus
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <section class="bus-routes">
      <div class="container">
        <div class="section-header">
          <span class="badge">Trajets</span>
          <h2>Les trajets</h2>
        </div>
        <div class="route-cards" id="les-trajets">
          <?php foreach ($trajetlist as $trajet):
            $isFavorite = $controller_trajet->isTrajetFavori($trajet['id_trajet'], $_SESSION['user_id']);
            ?>
            <div class="route-card" data-trajet-id="<?= $trajet['id_trajet'] ?>">
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
                  <div class="detail">
                    <button class="favoris-btn <?= $isFavorite ? 'favorited' : '' ?>"
                      data-trajet-id="<?= $trajet['id_trajet'] ?>">
                      <i class="fas fa-heart"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div class="route-price">
                <span class="price"><?= htmlspecialchars($trajet['prix']) ?> TND</span>
                <button class="btn btn-primary toggle-info-btn" type="button" data-id="<?= $trajet['id_trajet'] ?>">
                  Informations sur les bus
                </button>
              </div>
              <div id="bus-info-modal-<?= $trajet['id_trajet'] ?>" class="modal">
                <div class="modal-content">
                  <span class="close-btn"
                    onclick="closeModal('bus-info-modal-<?= $trajet['id_trajet'] ?>')">&times;</span>
                  <div class="modal-header">
                    <h2>Informations sur les bus</h2>
                  </div>
                  <div class="modal-body">
                    <?php
                    $buses = $controller_trajet->getBusesByTrajetId($trajet['id_trajet'], $_SESSION['user_id']);
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
                              data-bus-num="<?= $bus['num_bus'] ?>">Annuler la réservation</button>
                          <?php else: ?>
                            <button class="reserver-btn" data-bus-id="<?= $bus['id_bus'] ?>"
                              data-bus-num="<?= $bus['num_bus'] ?>">Réserver ce bus</button>
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
          <?php endforeach; ?>
        </div>
    </section>

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
    <!-- Payment Choice Modal -->
<div id="paymentChoiceModal" class="modal">
  <div class="modal-content" style="max-width:340px; text-align:center;">
    <div class="modal-header" style="justify-content:center;">
      <h2 style="font-size:1.2rem;">Choisissez le mode de paiement</h2>
      <button class="close-btn" onclick="closeModal('paymentChoiceModal')">&times;</button>
    </div>
    <div class="modal-body" style="padding:2rem 1.5rem 1.5rem;">
      <button id="payByCardBtn" class="btn btn-primary" style="width:100%;margin-bottom:1rem;font-size:0.9rem;">Paiement par carte</button>
      <button id="payByCashBtn" class="btn btn-primary" style="width:100%;font-size:0.9rem;">Paiement en espèces</button>
    </div>
  </div>
</div>
    <!-- Credit Card Modal -->
    <div id="creditCardModal" class="modal">
      <div class="modal-content" style="max-width:420px;">
        <div class="modal-header">
          <h2>Paiement par carte</h2>
          <button class="close-btn" onclick="closeModal('creditCardModal')">&times;</button>
        </div>
        <div class="modal-body">
          <div id="card-wrapper">
          </div>
          <br>
          <form id="creditCardForm" autocomplete="off">
            <div class="form-group">
              <input placeholder="Numéro de carte" type="tel" name="number" id="cc-number" required>
            </div>
            <div class="form-group">
              <input placeholder="Nom sur la carte" type="text" name="name" id="cc-name" required>
            </div>
            <div class="form-group" style="display:flex;gap:1rem;">
              <input placeholder="MM/AA" type="tel" name="expiry" id="cc-expiry" required style="flex:1;">
            </div>
            <div class="form-group" style="display:flex;gap:1rem;">
              <input placeholder="CVC" type="tel" name="cvc" id="cc-cvc" required style="flex:1;">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1.5rem;">Valider le
              paiement</button>
          </form>
        </div>
      </div>
    </div>

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
            <li><a href="View/FrontOffice/bus/index.php">Bus</a></li>
            <li><a href="View/FrontOffice/covoiturage/index.php">Covoiturage</a></li>
            <li><a href="View/FrontOffice/colis/index.php">Colis</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>À propos</h4>
          <ul>
            <li><a href="#">Notre mission</a></li>
            <li><a href="View/FrontOffice/blog/index.php">Blog</a></li>
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
            <li><i class="fas fa-phone"></i> +216 26 216 216</li>
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
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>


</body>

</html>