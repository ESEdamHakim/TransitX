<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>TransitX | Gestion des Bus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Ajoutez et gérez les bus pour les trajets avec TransitX." />
  <meta name="author" content="TransitX Team" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Bootstrap & Custom CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/styles.css" />

  <style>
    body {
      background-color: #ffffff;
      font-family: 'Segoe UI', sans-serif;
      color: #1f4f65;
    }

    header {
      background-color: #97c3a2;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      gap: 260px;
    }

    .logo-text {
      font-size: 24px;
      font-weight: bold;
      color: #1f4f65;
      margin-left: 10px;
    }

    nav a {
      font-size: 18px;
      font-weight: 500;
      color: #fff;
      margin-left: 30px;
      transition: 0.3s;
    }

    nav a.active, nav a:hover {
      color: #f9d86d;
    }

    h3 {
      color: #1f4f65;
      margin-bottom: 15px;
    }

    .form-control {
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .btn-success {
      background-color: #97c3a2;
      border: none;
      color: white;
      font-weight: bold;
    }

    .btn-success:hover {
      background-color: #1f4f65;
    }

    .btn-palette-green {
      background-color: #97c3a2;
      border: none;
      color: white;
      font-weight: bold;
      padding: 5px 12px;
      border-radius: 4px;
    }

    .btn-palette-green:hover {
      background-color: #7ca88a;
    }

    .btn-palette-dark {
      background-color: #1f4f65;
      border: none;
      color: white;
      font-weight: bold;
      padding: 5px 12px;
      border-radius: 4px;
    }

    .btn-palette-dark:hover {
      background-color: #173b4c;
    }
  </style>
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
      <a href="bus.php" class="active">Bus</a>
      <a href="colis.php">Colis</a>
      <a href="#">Covoiturage</a>
      <a href="#">Blog</a>
      <a href="#">À propos</a>
      <a href="#">Contact</a>
    </nav>
  </header>

  <!-- Main Content -->
  <section class="py-5">
    <div class="container">
      <div class="row">
        <!-- Formulaire -->
        <div class="col-md-12 mb-5">
          <h3>Ajouter un Bus</h3>
          <form method="POST" action="bus_handler.php">
            <div class="form-group mb-3">
              <label for="id_bus">ID Bus</label>
              <input type="text" class="form-control" name="id_bus" required>
            </div>

            <div class="form-group mb-3">
              <label for="numero_bus">Numéro Bus</label>
              <input type="text" class="form-control" name="numero_bus" required>
            </div>

            <div class="form-group mb-3">
              <label for="capacite">Capacité</label>
              <input type="number" class="form-control" name="capacite" required>
            </div>

            <div class="form-group mb-3">
              <label for="type_bus">Type de Bus</label>
              <input type="text" class="form-control" name="type_bus" required>
            </div>

            <h3>Informations sur le Trajet</h3>

            <div class="form-group mb-3">
              <label for="id_trajet">ID Trajet</label>
              <input type="text" class="form-control" name="id_trajet" required>
            </div>

            <div class="form-group mb-3">
              <label for="nom_trajet">Nom du Trajet</label>
              <input type="text" class="form-control" name="nom_trajet" required>
            </div>

            <div class="form-group mb-3">
              <label for="duree">Durée</label>
              <input type="text" class="form-control" name="duree" required>
            </div>

            <div class="form-group mb-3">
              <label for="frequence">Fréquence</label>
              <input type="text" class="form-control" name="frequence" required>
            </div>

            <div class="form-group mb-4">
              <label for="arrets">Arrêts</label>
              <textarea class="form-control" name="arrets" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Ajouter</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Liste des Bus -->
  <section class="py-5 bg-light">
    <div class="container">
      <h3 class="mb-4">Liste des Bus</h3>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark text-center">
            <tr>
              <th>ID Bus</th>
              <th>Numéro</th>
              <th>Capacité</th>
              <th>Type</th>
              <th>ID Trajet</th>
              <th>Nom Trajet</th>
              <th>Durée</th>
              <th>Fréquence</th>
              <th>Arrêts</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td>BUS001</td>
              <td>TX-101</td>
              <td>50</td>
              <td>Standard</td>
              <td>TR001</td>
              <td>Tunis - Sousse</td>
              <td>2h 30min</td>
              <td>Quotidienne</td>
              <td>Bab Saadoun, Hammam-Lif, Mornag</td>
              <td>
                <button class="btn btn-sm btn-palette-green me-1">
                  <i class="fa fa-edit"></i> Modifier
                </button>
                <button class="btn btn-sm btn-palette-dark">
                  <i class="fa fa-trash"></i> Supprimer
                </button>
              </td>
            </tr>
            <tr>
              <td>BUS002</td>
              <td>TX-202</td>
              <td>40</td>
              <td>Mini</td>
              <td>TR002</td>
              <td>Ariana - La Marsa</td>
              <td>45min</td>
              <td>Toutes les 2 heures</td>
              <td>El Menzah 6, Lac 1, Carthage</td>
              <td>
                <button class="btn btn-sm btn-palette-green me-1">
                  <i class="fa fa-edit"></i> Modifier
                </button>
                <button class="btn btn-sm btn-palette-dark">
                  <i class="fa fa-trash"></i> Supprimer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</body>
</html>
