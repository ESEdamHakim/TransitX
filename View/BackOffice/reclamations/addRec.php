<?php
require_once '../../../Controller/ReclamationController.php';

$ReclamationC = new ReclamationController();

// Always load dropdown data
$covoiturages = $ReclamationC->getAllCovoiturages();
$clients = $ReclamationC->getAllClients();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (
    isset(
    $_POST['id_client'],
    $_POST['statut'],
    $_POST['date_rec'],
    $_POST['objet'],
    $_POST['description'],
    $_POST['id_covoit']
  )
  ) {
    $ReclamationC->addReclamation(
      $_POST['id_client'],
      $_POST['id_covoit'],
      $_POST['objet'],
      $_POST['description'],
      $_POST['date_rec'],
      $_POST['statut']
    );
    header("Location: crud.php");
    exit();
  } else {
    echo "Erreur : tous les champs obligatoires ne sont pas remplis.";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Ajouter une Réclamation</title>

  <!-- css Imports -->
  <link rel="stylesheet" href="assets/css/reclamation.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../../assets/css/main.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

</head>

<body>
  <div class="dashboard">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">

      <section class="section">
        <div class="container">
          <div class="section-header">
            <br>
            <h2>Nouvelle Réclamation
              <p>Merci de nous faire part de votre problème via le formulaire ci-dessous.</p>
            </h2>
          </div>

          <div class="container">
            <form class="reclamation-form" method="POST">

              <div class="form-section">
                <h3 class="form-title">Détails de la réclamation</h3>

                <div class="form-group">
                  <label for="id_client">Client :</label>
                  <select name="id_client" id="id_client">
                    <option value="">-- Sélectionner un client --</option>
                    <?php foreach ($clients as $client): ?>
                      <option value="<?= $client['id'] ?>">
                        <?= $client['nom'] ?>   <?= $client['prenom'] ?> (ID: <?= $client['id'] ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="complaint-type">Objet de la réclamation</label>
                  <select id="complaint-type" name="objet">
                    <option value="">-- Sélectionner un objet --</option>
                    <option value="Retard">Retard</option>
                    <option value="Annulation">Annulation</option>
                    <option value="Dommage">Dommage</option>
                    <option value="Qualité de service">Qualité de service</option>
                    <option value="Facturation">Facturation</option>
                    <option value="Autre">Autre</option>
                  </select>
                </div>

                <div class="form-row">
                  <div class="form-group half">
                    <label for="incident-date">Date de l'incident</label>
                    <input type="date" name="date_rec" id="incident-date">
                  </div>
                  <div class="form-group">
                    <label for="id_covoit">Covoiturage :</label>
                    <select name="id_covoit" id="id_covoit">
                      <option value="">-- Sélectionner un covoiturage --</option>
                      <?php foreach ($covoiturages as $cov): ?>
                        <option value="<?= $cov['id_covoit'] ?>">
                          <?= $cov['lieu_depart'] ?> → <?= $cov['lieu_arrivee'] ?> (ID: <?= $cov['id_covoit'] ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="description">Description détaillée</label>
                  <textarea name="description" id="description" rows="5"
                    placeholder="Expliquez votre situation..."></textarea>
                </div>

                <div class="form-group">
                  <label for="statut">Statut</label>
                  <select name="statut" id="statut">
                    <option value="">-- Sélectionner un statut --</option>
                    <option value="En attente">En attente</option>
                    <option value="En cours">En cours de traitement</option>
                    <option value="Résolue">Résolue</option>
                    <option value="Rejetée">Rejetée</option>
                  </select>
                </div>

                <div class="form-actions">
                  <a href="crud.php" class="btn btn-outline">
                    Annuler <i class="fas fa-times"></i>
                  </a>
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Ajouter
                  </button>
                </div>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    // Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        // Filter colis based on tab (for a real application, this would use AJAX to fetch filtered data)
        const tabName = this.getAttribute('data-tab');
        console.log(`Switching to tab: ${tabName}`);
      });
    });
  </script>
  <script src="assets/js/recValidation.js"></script>
</body>

</html>