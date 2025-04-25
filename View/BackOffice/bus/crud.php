<?php
include("../../../Controller/buscontroller.php");
include("../../../Controller/trajetcontroller.php");

$controller = new BusController();
$buslist = $controller->listBuses();

$controller_trajet = new TrajetController();
$trajetlist = $controller_trajet->listTrajets();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Gestion des Bus</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
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
            <li class="active">
              <a href="crud.php">
                <i class="fas fa-bus"></i>
                <span>Bus</span>
              </a>
            </li>
            <li>
              <a href="../colis/crud.php">
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
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Bus et des Trajets </h1>
          <p>Ajoutez, modifiez et supprimez des bus et des trajets</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un bus...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un trajet...">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addbus.php" class="btn primary" id="add-bus-btn" style="padding: 4px 8px; font-size: 12px;">
              <i class="fas fa-plus"></i> Ajouter un Bus
            </a>
          </div>

          <div class="actions">
            <a href="addtrajet.php" class="btn primary" id="add-trajet-btn" style="padding: 4px 8px; font-size: 12px;">
              <i class="fas fa-plus"></i> Ajouter un Trajet
            </a>

          </div>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="crud-container">
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Bus</button>
              <button class="tab-btn" data-tab="active">Actifs</button>
              <button class="tab-btn" data-tab="maintenance">En Maintenance</button>
              <button class="tab-btn" data-tab="inactive">Inactifs</button>
            </div>
          </div>

          <!-- Table View -->
          <div class="view-container table-view active">
            <div class="buses-table-container">
              <table class="buses-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>ID trajet</th>
                    <th>Numéro du Bus</th>
                    <th>Capacité</th>
                    <th>Type de Bus</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Date de Mise en Service</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($buslist as $bus): ?>
                    <tr>
                      <td><?= htmlspecialchars($bus['id_bus']) ?></td>
                      <td><?= htmlspecialchars($bus['id_trajet']) ?></td>
                      <td><?= htmlspecialchars($bus['num_bus']) ?></td>
                      <td><?= htmlspecialchars($bus['capacite']) ?></td>
                      <td><?= htmlspecialchars($bus['type_bus']) ?></td>
                      <td><?= htmlspecialchars($bus['marque']) ?></td>
                      <td><?= htmlspecialchars($bus['modele']) ?></td>
                      <td><?= htmlspecialchars($bus['date_mise_en_service']) ?></td>
                      <td>
                        <span class="status<?= strtolower($bus['statut']) ?>">
                          <?= htmlspecialchars($bus['statut']) ?>
                        </span>
                      </td>
                      <td class="actions">
                        <form method="GET" action="updatebus.php" style="display:inline;">
                          <input type="hidden" name="id_bus" value="<?= htmlspecialchars($bus['id_bus']) ?>">
                          <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                        </form>
                        <form method="GET" action="deletebus.php" onsubmit="return confirm('Êtes-vous sûr ?');"
                          style="display:inline;">
                          <input type="hidden" name="id_bus" value="<?= htmlspecialchars($bus['id_bus']) ?>">
                          <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>


          <!-- Table View -->
          <div class="buses-table-container">
            <table class="buses-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Lieu de Départ</th>
                  <th>Lieu d'Arrivée</th>
                  <th>Heure de Départ</th>
                  <th>Durée</th>
                  <th>Distance (km)</th>
                  <th>Prix (TND)</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($trajetlist as $trajet): ?>
                  <tr>
                    <td><?= htmlspecialchars($trajet['id_trajet']) ?></td>
                    <td><?= htmlspecialchars($trajet['place_depart']) ?></td>
                    <td><?= htmlspecialchars($trajet['place_arrivee']) ?></td>
                    <td><?= htmlspecialchars($trajet['heure_depart']) ?></td>
                    <td><?= htmlspecialchars($trajet['duree']) ?></td>
                    <td><?= htmlspecialchars($trajet['distance_km']) ?></td>
                    <td><?= htmlspecialchars($trajet['prix']) ?></td>
                    <td class="actions">
                      <form method="GET" action="updatetrajet.php" style="display:inline;">
                        <input type="hidden" name="id_trajet" value="<?= htmlspecialchars($trajet['id_trajet']) ?>">
                        <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      </form>
                      <form method="GET" action="deletetrajet.php" onsubmit="return confirm('Êtes-vous sûr ?');"
                        style="display:inline;">
                        <input type="hidden" name="id_trajet" value="<?= htmlspecialchars($trajet['id_trajet']) ?>">
                        <button class="action-btn delete" title="Supprimer"><i class="fas fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
    </main>
  </div>

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

        // Filter buses based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });
  </script>
</body>

</html>