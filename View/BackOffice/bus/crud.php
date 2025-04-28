<?php
require_once "../../../Controller/buscontroller.php";
$busController = new BusController();
$buslist = $busController->listBuses();
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
    <?php include 'sidebar.php'; ?>
    <!-- Main Content -->
    <main class="main-content">
      <header class="dashboard-header">
        <div class="header-left">
          <h1>Gestion des Bus</h1>
          <p>Ajoutez, modifiez et supprimez des bus</p>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <input type="text" placeholder="Rechercher un bus par numéro" aria-label="Rechercher un bus">
            <button><i class="fas fa-search"></i></button>
          </div>
          <div class="actions">
            <a href="addbus.php" class="btn primary"><i class="fas fa-plus"></i> Ajouter un Bus</a>
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
                    <th>ID</th>
                    <th>ID Trajet</th>
                    <th>Numéro</th>
                    <th>Capacité</th>
                    <th>Type</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Mise en Service</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($buslist as $bus): ?>
                    <tr data-statut="<?= strtolower($bus['statut']) ?>">
                      <?php foreach (['id_bus', 'id_trajet', 'num_bus', 'capacite', 'type_bus', 'marque', 'modele', 'date_mise_en_service'] as $field): ?>
                        <td><?= htmlspecialchars($bus[$field]) ?></td>
                      <?php endforeach; ?>
                      <td><span
                          class="status<?= strtolower($bus['statut']) ?>"><?= htmlspecialchars($bus['statut']) ?></span>
                      </td>
                      <td class="actions">
                        <form method="GET" action="updatebus.php" style="display:inline;">
                          <input type="hidden" name="id_bus" value="<?= htmlspecialchars($bus['id_bus']) ?>">
                          <button class="action-btn edit" title="Modifier"><i class="fas fa-edit"></i></button>
                        </form>
                        <button type="button" class="action-btn delete open-delete-modal" title="Supprimer"
                          data-id="<?= htmlspecialchars($bus['id_bus']) ?>">
                          <i class="fas fa-trash"></i>
                        </button>

                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <div class="modal" id="delete-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirmer la suppression</h2>
        <button class="close-modal"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce bus ? Cette action est irréversible.</p>
        <div class="form-actions">
          <button type="button" class="btn secondary cancel-btn">Annuler</button>
          <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
        </div>
      </div>
    </div>

    <!-- Hidden Delete Form -->
    <form method="POST" action="deletebus.php" style="display:none;" id="delete-form">
      <input type="hidden" name="id_bus" id="delete-id">
    </form>   
    <script src="assets/js/main.js"></script>
</body>

</html>