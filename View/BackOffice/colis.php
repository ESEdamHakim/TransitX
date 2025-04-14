<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>TransitX | Gestion des Colis</title>
  <link rel="stylesheet" href="assets/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .colis-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: space-between;
      padding: 20px;
    }

    .colis-card {
      position: relative;
      width: 23%;
      aspect-ratio: 1/1;
      overflow: hidden;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      background-color: #fff;
      transition: transform 0.3s ease;
    }

    .colis-card:hover {
      transform: translateY(-5px);
    }

    .colis-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .colis-info {
      position: absolute;
      bottom: 0;
      background: rgba(0, 0, 0, 0.75);
      color: white;
      width: 100%;
      padding: 10px;
      font-size: 12px;
      transform: translateY(100%);
      transition: transform 0.3s ease;
      overflow-y: auto;
      max-height: 100%;
    }

    .colis-card:hover .colis-info {
      transform: translateY(0);
    }

    .colis-info p {
      margin: 3px 0;
      line-height: 1.3;
    }

    .action-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 8px;
    }

    .action-buttons button {
      background: rgba(255, 255, 255, 0.1);
      border: none;
      color: white;
      cursor: pointer;
      font-size: 12px;
      padding: 4px 8px;
      border-radius: 4px;
    }

    .action-buttons button:hover {
      background: rgba(255, 255, 255, 0.25);
    }

    /* New button positioning */
    .add-btn {
  margin-left: 20px;  /* Move the button slightly to the left */
  padding: 10px 20px;
  background-color: #1f4f65; /* Your secondary color */
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  z-index: 10;
}

.add-btn:hover {
  background-color: #97c3a2; /* Your primary color */
}


    @media (max-width: 1024px) {
      .colis-card {
        width: 48%;
      }
    }

    @media (max-width: 600px) {
      .colis-card {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <img src="assets/TransitXLogo.png" alt="Logo TransitX" />
        <h2>TransitX</h2>
      </div>
      <nav class="sidebar-nav">
        <a href="index.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
        <a href="#"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="bus.php"><i class="fas fa-bus"></i> Bus</a>
        <a href="colis.php" class="active"><i class="fas fa-box"></i> Colis</a>
        <a href="#"><i class="fas fa-car-side"></i> Covoiturage</a>
        <a href="#"><i class="fas fa-blog"></i> Blog</a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: center;">
  <h1>Gestion des Colis</h1>
  <!-- Ajouter button -->
  <button class="add-btn" onclick="window.location.href='../FrontOffice/colis.php'">
  <i class="fas fa-plus"></i> Ajouter
</button>

</header>


      <section class="colis-container">
        <?php
          $colisList = [];
          for ($i = 1; $i <= 8; $i++) {
            $colisList[] = [
              'id' => $i,
              'id_client' => rand(10000, 99999),
              'adresse_colis' => "Adresse $i, Rue Exemple",
              'statut_colis' => $i % 3 === 0 ? 'En attente' : ($i % 2 === 0 ? 'Livré' : 'En cours'),
              'date_colis' => '2025-04-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
              'id_categorie' => 'CAT-0' . $i,
              'description' => "Description du colis numéro $i. Fragile ou urgent selon le cas."
            ];
          }

          foreach ($colisList as $colis):
        ?>
          <div class="colis-card">
            <img src="assets/colis.jpg" alt="Colis Image">
            <div class="colis-info">
              <p><strong>ID Client:</strong> <?= $colis['id_client'] ?></p>
              <p><strong>Adresse:</strong> <?= $colis['adresse_colis'] ?></p>
              <p><strong>Statut:</strong> <?= $colis['statut_colis'] ?></p>
              <p><strong>Date Livraison:</strong> <?= $colis['date_colis'] ?></p>
              <p><strong>ID Catégorie:</strong> <?= $colis['id_categorie'] ?></p>
              <p><strong>Description:</strong> <?= $colis['description'] ?></p>
              <div class="action-buttons">
                <button onclick="editColis(<?= $colis['id'] ?>)"><i class="fas fa-edit"></i> Modifier</button>
                <button onclick="deleteColis(<?= $colis['id'] ?>)"><i class="fas fa-trash-alt"></i> Supprimer</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </section>
    </main>
  </div>

  <script>
    function editColis(id) {
      window.location.href = `edit_colis.php?id=${id}`;
    }

    function deleteColis(id) {
      if (confirm("Êtes-vous sûr de vouloir supprimer ce colis ?")) {
        window.location.href = `delete_colis.php?id=${id}`;
      }
    }
  </script>
</body>
</html>
