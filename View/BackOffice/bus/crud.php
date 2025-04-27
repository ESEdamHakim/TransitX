<?php
require_once "../../../Controller/buscontroller.php";
require_once "../../../Controller/trajetcontroller.php";

// Controllers
$busController = new BusController();
$trajetController = new TrajetController();

$buslist = $busController->listBuses();
$trajetlist = $trajetController->listTrajets();
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
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
          <span>Transit</span><span class="highlight">X</span>
        </div>
        <button class="sidebar-toggle" aria-label="Toggle sidebar">
          <i class="fas fa-bars"></i>
        </button>
      </div>

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

    <!-- Main Content -->
    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Bus et des Trajets</h1>
          <p>Ajoutez, modifiez et supprimez des bus et des trajets</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un bus..." aria-label="Rechercher un bus">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un trajet..." aria-label="Rechercher un trajet">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addbus.php" class="btn primary"><i class="fas fa-plus"></i> Ajouter un Bus</a>
            <a href="addtrajet.php" class="btn primary"><i class="fas fa-plus"></i> Ajouter un Trajet</a>
          </div>
        </div>
      </header>

      <div class="dashboard-content">
        <div class="crud-container">
          <!-- Tabs -->
          <div class="crud-header">
            <div class="tabs">
              <button class="tab-btn active" data-tab="all">Tous les Bus</button>
              <button class="tab-btn" data-tab="actif">Actif</button>
              <button class="tab-btn" data-tab="maintenance">En Maintenance</button>
              <button class="tab-btn" data-tab="inactif">Inactifs</button>
            </div>
          </div>

          <!-- Bus Table -->
          <div class="view-container table-view active">
            <div class="buses-table-container">
              <table class="buses-table">
                <thead>
                  <tr>
                    <th>ID</th><th>ID Trajet</th><th>Numéro</th><th>Capacité</th><th>Type</th>
                    <th>Marque</th><th>Modèle</th><th>Mise en Service</th><th>Statut</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($buslist as $bus): ?>
                    <tr data-statut="<?= strtolower($bus['statut']) ?>">
                      <?php foreach (['id_bus', 'id_trajet', 'num_bus', 'capacite', 'type_bus', 'marque', 'modele', 'date_mise_en_service'] as $field): ?>
                        <td><?= htmlspecialchars($bus[$field]) ?></td>
                      <?php endforeach; ?>
                      <td><span class="status<?= strtolower($bus['statut']) ?>"><?= htmlspecialchars($bus['statut']) ?></span></td>
                      <td class="actions">
                        <form method="GET" action="updatebus.php" style="display:inline;">
                          <input type="hidden" name="id_bus" value="<?= htmlspecialchars($bus['id_bus']) ?>">
                          <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                        </form>
                        <form method="GET" action="deletebus.php" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?');">
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

          <!-- Trajet Table -->
          <div class="buses-table-container">
            <table class="buses-table">
              <thead>
                <tr>
                  <th>ID</th><th>Départ</th><th>Arrivée</th><th>Heure</th>
                  <th>Durée</th><th>Distance (km)</th><th>Prix (TND)</th><th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($trajetlist as $trajet): ?>
                  <tr>
                    <?php foreach (['id_trajet', 'place_depart', 'place_arrivee', 'heure_depart', 'duree', 'distance_km', 'prix'] as $field): ?>
                      <td><?= htmlspecialchars($trajet[$field]) ?></td>
                    <?php endforeach; ?>
                    <td class="actions">
                      <form method="GET" action="updatetrajet.php" style="display:inline;">
                        <input type="hidden" name="id_trajet" value="<?= htmlspecialchars($trajet['id_trajet']) ?>">
                        <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                      </form>
                      <form method="GET" action="deletetrajet.php" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?');">
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
      </div>
    </main>
  </div>
  <script src="assets/js/main.js"></script>
</body>

</html>
