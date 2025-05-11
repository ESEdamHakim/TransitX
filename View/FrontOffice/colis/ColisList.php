<?php
require_once __DIR__ . '/../../../Controller/ColisController.php';

session_start();

$ColisC = new ColisController();
$list = $ColisC->listColis();
$listByCovoit = $ColisC->getColisByCovoiturage($_SESSION['user_id']);
$covoiturages = $ColisC->getAllCovoiturages();
$clients = $ColisC->getAllClients();
$notifications = $ColisC->getNotificationByIdUser($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Mes Colis</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/colis.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="assets/css/chatbot.css">
</head>
<style>
  /* Additional styles specific to colis list */
  .colis-dashboard {
    padding: 3rem 5%;
    background-color: var(--background);
  }

  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }

  .dashboard-title {
    color: var(--secondary);
  }

  .dashboard-title h1 {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
  }

  .dashboard-title p {
    color: #666;
  }

  .dashboard-actions {
    display: flex;
    gap: 1rem;
  }

  .filters-section {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .filters-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .filters-title h3 {
    font-size: 1.2rem;
    color: var(--secondary);
    margin: 0;
  }

  .filters-toggle {
    background: none;
    border: none;
    color: var(--primary);
    cursor: pointer;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .filters-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
  }

  .filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .filter-group label {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
  }

  .filter-group select,
  .filter-group input {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f8f9fa;
  }

  .filter-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
  }

  .tabs-container {
    display: flex;
    border-bottom: 1px solid #ddd;
    margin-bottom: 1.5rem;
    overflow-x: auto;
  }

  .tab {
    padding: 1rem 1.5rem;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s;
    white-space: nowrap;
  }

  .tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
  }

  .tab:hover {
    color: var(--primary);
  }

  .tab .count {
    display: inline-block;
    background-color: #f1f1f1;
    color: #666;
    font-size: 0.8rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    margin-left: 0.5rem;
  }

  .tab.active .count {
    background-color: var(--primary);
    color: white;
  }

  .action {
    display: flex;
    gap: 0.5rem;
  }

  .empty-state {
    text-align: center;
    padding: 3rem;
    color: #666;
  }

  .empty-state i {
    font-size: 3rem;
    color: #ddd;
    margin-bottom: 1rem;
  }

  .empty-state h3 {
    margin-bottom: 0.5rem;
    color: #333;
  }

  @media (max-width: 992px) {
    .dashboard-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .dashboard-actions {
      width: 100%;
    }

    .filters-content {
      grid-template-columns: 1fr;
    }
  }
</style>
</head>

<body>
  <?php include 'chatbot.php'; ?>
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
         <button class="notify-button position-relative" title="Notifications">
          <i class="fa-solid fa-bell"></i>
          <?php if (count($notifications) > 0): ?>
            <span class="notif-badge"><?= count($notifications) ?></span>
          <?php endif; ?>
        </button>
      </div>
    </div>
  </header>

  <main>
    <section class="colis-dashboard">
      <div class="container">
        <div class="dashboard-header">
          <div class="dashboard-title">
            <h1>Mes Colis</h1>
            <p>Gérez et suivez tous vos envois de colis</p>
          </div>
          <div class="dashboard-actions">
            <a href="index.php" class="btn btn-primary">
              <i class="fas fa-plus"></i> Nouveau Colis
            </a>
          </div>
        </div>

        <!-- FILTERS -->
        <div class="filters-section">
          <div class="filters-title">
            <h3>Filtres</h3>
            <button class="filters-toggle">
              <i class="fas fa-sliders-h"></i> Afficher les filtres
            </button>
          </div>
          <div class="filters-content">
            <div class="filter-group">
              <label for="status-filter">Statut</label>
              <select id="status-filter">
                <option value="all">Tous les statuts</option>
                <option value="pending">En attente</option>
                <option value="in-progress">En transit</option>
                <option value="resolved">Livré</option>
              </select>
            </div>
            <div class="filter-group">
              <label for="date-filter">Date d'envoi</label>
              <input type="date" id="date-filter">
            </div>
            <div class="filter-group" style="display: none;">
  <label for="search-filter">Recherche</label>
  <input type="text" id="search-filter" placeholder="ID covoiturage">
</div>
            <div class="filter-group">
              <label for="price-sort">Tri par prix</label>
              <select id="price-sort">
                <option value="none">Aucun tri</option>
                <option value="asc">Prix croissant</option>
                <option value="desc">Prix décroissant</option>
              </select>
            </div>
          </div>
          <div class="filter-actions">
            <button type="button" class="btn btn-outline reset-btn">Réinitialiser</button>
            <button type="button" class="btn btn-primary apply-btn">Appliquer</button>
          </div>
        </div>

        <!-- TABS -->
        <div class="tabs-container">
          <div class="tab" data-status="all">Tous <span class="count">0</span></div>
          <div class="tab" data-status="pending">En attente <span class="count">0</span></div>
          <div class="tab" data-status="in-progress">En transit <span class="count">0</span></div>
          <div class="tab" data-status="resolved">Livrés <span class="count">0</span></div>
        </div>
        <div class="collapsible-section" id="affectes-covoiturage">
          <div class="collapsible-header">
            Colis Affectés à Mes Covoiturages
            <span class="icon">▼</span>
          </div>
          <div class="collapsible-content">
            <?php if (empty($listByCovoit)): ?>
              <p style="text-align: center; padding: 20px; color: #555;">
                Aucun colis n'est affecté à vos covoiturages pour le moment.
              </p>
            <?php else: ?>
              <div class="route-cards">
                <?php foreach ($listByCovoit as $colis):
                  $covoit = null;
                  $client = null;

                  if (!empty($colis['id_covoit'])) {
                    $covoit = $ColisC->getCovoiturageById($colis['id_covoit']);

                    if ($covoit && isset($covoit['id_user'])) {
                      $client = $ColisC->getClientById($colis['id_client']);
                    }
                  }

                  $statusClassMap = [
                    'en attente' => 'pending',
                    'en transit' => 'in-progress',
                    'livré' => 'resolved'
                  ];

                  $statut = trim($colis['statut']);
                  $className = $statusClassMap[$statut] ?? 'default';
                  ?>

                  <div class="route-card" id="colis-<?= $colis['id_colis'] ?>" data-status="<?= $className ?>"
                    data-date="<?= htmlspecialchars($colis['date_colis']) ?>"
                    data-price="<?= htmlspecialchars($colis['prix']) ?>">

                    <div class="route-info">
                      <div class="route-cities">
                        <span class="departure"><?= htmlspecialchars($colis['lieu_ram']) ?></span>
                        <i class="fas fa-long-arrow-alt-right"></i>
                        <span class="arrival"><?= htmlspecialchars($colis['lieu_dest']) ?></span>
                      </div>

                      <div class="route-details">
                        <div class="detail">
                          <i class="fas fa-user"></i>
                          <?php if ($client): ?>
                            Expéditeur : <?= htmlspecialchars($client['nom']) ?>       <?= htmlspecialchars($client['prenom']) ?>
                            (ID: <?= htmlspecialchars($client['id']) ?>)
                          <?php else: ?>
                            <em>Client inconnu</em>
                          <?php endif; ?>
                        </div>
                        <div class="detail">
                          <i class="fas fa-box"></i>
                          <span>
                            L: <?= number_format($colis['longueur'], 2) ?> ×
                            l: <?= number_format($colis['largeur'], 2) ?> ×
                            H: <?= number_format($colis['hauteur'], 2) ?> cm
                          </span>
                        </div>
                        <div class="detail">
                          <i class="fas fa-weight-hanging"></i>
                          <span> <?= number_format($colis['poids'], 2) ?> kg</span>
                        </div>
                        <div class="detail">
                          <i class="fas fa-calendar-alt"></i>
                          <span> <?= htmlspecialchars($colis['date_colis']) ?></span>
                        </div>
                        <div class="detail">
                          <i class="fas fa-info-circle"></i>
                          <span class="status <?= $className ?>">
                            <?= htmlspecialchars($colis['statut']) ?>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="route-price">
                      <span class="price"><?= htmlspecialchars($colis['prix']) ?> DT</span>
                      <div class="action" style="margin-top: 10px; display: flex; gap: 5px;">
                        <form method="GET" action="updateColisOfCovoit.php" style="display:inline;">
                          <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">
                          <button type="submit" class="action-btn edit" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="collapsible-section">
          <div class="collapsible-header">
            Mes Colis
            <span class="icon">▼</span>
          </div>
          <div class="collapsible-content">
            <?php foreach ($list as $colis):
              $covoit = null;
              $client = null;

              // Fetch covoiturage and client only if id_covoit is not empty
              if (!empty($colis['id_covoit'])) {
                $covoit = $ColisC->getCovoiturageById($colis['id_covoit']);

                if ($covoit && isset($covoit['id_user'])) {
                  $client = $ColisC->getClientById($covoit['id_user']);
                }
              }

              // Skip if the client is not 
              if ($colis['id_client'] != $_SESSION['user_id']) {
                continue;
              }
              // Map the status to a class for styling
              $statusClassMap = [
                'en attente' => 'pending',
                'en transit' => 'in-progress',
                'livré' => 'resolved'
              ];

              $statut = trim($colis['statut']);
              $className = isset($statusClassMap[$statut]) ? $statusClassMap[$statut] : 'default';
              ?>

              <div class="route-card" data-status="<?= $className ?>"
                data-date="<?= htmlspecialchars($colis['date_colis']) ?>"
                data-price="<?= htmlspecialchars($colis['prix']) ?>"
                data-covoit-id="<?= $covoit ? htmlspecialchars($covoit['id_covoit']) : '' ?>">

                <div class="route-info">
                  <div class="route-cities">
                    <span class="departure"><?= htmlspecialchars($colis['lieu_ram']) ?></span>
                    <i class="fas fa-long-arrow-alt-right"></i>
                    <span class="arrival"><?= htmlspecialchars($colis['lieu_dest']) ?></span>
                  </div>

                  <div class="route-details">
                    <div class="detail">
                      <i class="fas fa-user"></i>
                      <?php if ($covoit): ?>
                        <?= htmlspecialchars($client['nom']) ?>     <?= htmlspecialchars($client['prenom']) ?> (ID:
                        <?= htmlspecialchars($client['id']) ?>)
                      <?php else: ?>
                        <em>Aucun covoiturage</em>
                      <?php endif; ?>
                    </div>
                    <div class="detail">
                      <i class="fas fa-box"></i>
                      <span>
                        L: <?= number_format($colis['longueur'], 2) ?> ×
                        l: <?= number_format($colis['largeur'], 2) ?> ×
                        H: <?= number_format($colis['hauteur'], 2) ?> cm
                      </span>
                    </div>
                    <div class="detail">
                      <i class="fas fa-weight-hanging"></i>
                      <span> <?= number_format($colis['poids'], 2) ?> kg</span>
                    </div>
                    <div class="detail">
                      <i class="fas fa-calendar-alt"></i>
                      <span> <?= htmlspecialchars($colis['date_colis']) ?></span>
                    </div>
                    <div class="detail">
                      <i class="fas fa-info-circle"></i>
                      <span class="status <?= $className ?>">
                        <?= htmlspecialchars($colis['statut']) ?>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="route-price">
                  <span class="price"><?= htmlspecialchars($colis['prix']) ?> DT</span>
                  <div class="action" style="margin-top: 10px; display: flex; gap: 5px;">
                    <form method="GET" action="updateColis.php" style="display:inline;">
                      <input type="hidden" name="id_colis" value="<?= htmlspecialchars($colis['id_colis']) ?>">
                      <button type="submit" class="action-btn edit" title="Modifier">
                        <i class="fas fa-edit"></i>
                      </button>
                    </form>
                    <button type="button" class="action-btn delete open-delete-modal"
                      data-id="<?= htmlspecialchars($colis['id_colis']) ?>" title="Supprimer">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
              <br>
            <?php endforeach; ?>
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


  <!-- Delete Confirmation Modal -->
  <div class="modal" id="delete-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hidden Delete Form -->
  <form method="POST" action="deleteColis.php" style="display:none;" id="delete-form">
    <input type="hidden" name="id_colis" id="delete-id">
  </form>

  <div id="notificationModal" class="notimodal-overlay hidden">
    <div class="notimodal-content">
      <div class="notimodal-header">
        <h3>Mes Notifications</h3>
        <button id="closeModal" class="close-btn">&times;</button>
      </div>
      <div class="notimodal-body">
        <div class="notifications-scroll">
          <?php foreach ($notifications as $notif): ?>
            <div class="notification-item card p-3 mb-3 shadow-sm rounded bg-light">
              <ul class="mb-2">
                <li><strong> Colis :</strong> <?= htmlspecialchars($notif['lieu_ram']) ?> ➜
                  <?= htmlspecialchars($notif['lieu_dest']) ?>
                </li>
                <li><strong>Date :</strong> <?= htmlspecialchars($notif['date_colis']) ?></li>
                <li><strong>Prix :</strong> <?= htmlspecialchars($notif['prix']) ?> TND</li>
                <li><strong>Dimensions (L×l×H) :</strong> <?= htmlspecialchars($notif['longueur']) ?>cm ×
                  <?= htmlspecialchars($notif['largeur']) ?>cm × <?= htmlspecialchars($notif['hauteur']) ?>cm
                </li>
                <li><strong>Poids :</strong> <?= htmlspecialchars($notif['poids']) ?> kg</li>
              </ul>

              <p class="mb-0">
                <strong>Covoiturage :</strong>
                <?= $notif['id_covoit'] ? "Affecté (ID : " . htmlspecialchars($notif['id_covoit']) . ")" : "<span class='text-muted'>Non encore affecté</span>" ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/colisValidation.js"></script>
  <script src="assets/js/colisDelete.js"></script>
  <script src="assets/js/colisFilters.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>

  <script>
    // Filters toggle
    document.querySelector('.filters-toggle').addEventListener('click', function () {
      const filtersContent = document.querySelector('.filters-content');
      const filterActions = document.querySelector('.filter-actions');

      if (filtersContent.style.display === 'none' || filtersContent.style.display === '') {
        filtersContent.style.display = 'grid';
        filterActions.style.display = 'flex';
        this.innerHTML = '<i class="fas fa-times"></i> Masquer les filtres';
      } else {
        filtersContent.style.display = 'none';
        filterActions.style.display = 'none';
        this.innerHTML = '<i class="fas fa-sliders-h"></i> Afficher les filtres';
      }
    });

    // Initially hide filters
    document.querySelector('.filters-content').style.display = 'none';
    document.querySelector('.filter-actions').style.display = 'none';

    document.querySelectorAll('.collapsible-header').forEach(header => {
      header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        content.classList.toggle('active');
      });
    });

    window.addEventListener('DOMContentLoaded', () => {
      const params = new URLSearchParams(window.location.search);
      const updatedColisId = params.get('updated_colis');

      if (updatedColisId) {
        // Simulate click on the collapsible header to expand it (always works)
        const header = document.querySelector('#affectes-covoiturage .collapsible-header');
        if (header) header.click(); // This will open it using your existing JS logic

        // Scroll to the specific colis after a short delay
        setTimeout(() => {
          const targetColis = document.getElementById(`colis-${updatedColisId}`);
          if (targetColis) {
            targetColis.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetColis.style.transition = 'box-shadow 0.3s ease';
            targetColis.style.boxShadow = '0 0 15px 4px #97c3a2';
            setTimeout(() => targetColis.style.boxShadow = '', 2000);
          }
        }, 500); // Delay to ensure collapsible is fully open
      }
    });
  </script>
  <script>
    const notifyBtn = document.querySelector('.notify-button');
    const modal = document.getElementById('notificationModal');
    const closeModal = document.getElementById('closeModal');

    notifyBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    // Optional: close modal when clicking outside
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  </script>

</body>

</html>