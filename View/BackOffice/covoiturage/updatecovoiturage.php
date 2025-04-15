<?php
require_once '../../../Controller/CovoiturageC.php';

$CovoiturageC = new CovoiturageC();

// Check if an ID is provided in the URL
if (!isset($_GET['id_covoit'])) {
    die("Invalid Request");
}

$id_covoit = $_GET['id_covoit'];
$covoiturage = $CovoiturageC->getCovoiturageById($id_covoit);

if (!$covoiturage) {
    die("Covoiturage not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $covoiturageToUpdate = new Covoiturage();
    $covoiturageToUpdate->setIdCovoit($id_covoit);
    $covoiturageToUpdate->setLieuDepart($_POST['lieu_depart']);
    $covoiturageToUpdate->setLieuArrivee($_POST['lieu_arrivee']);
    $covoiturageToUpdate->setDateDepart($_POST['date_depart']);
    $covoiturageToUpdate->setTempsDepart($_POST['temps_depart']);
    $covoiturageToUpdate->setPlacesDispo($_POST['places_dispo']);
    $covoiturageToUpdate->setPrix($_POST['prix']);
    $covoiturageToUpdate->setAccepteColis($_POST['accepte_colis']);
    $covoiturageToUpdate->setDetails($_POST['details']);

    $CovoiturageC->updateCovoiturage($covoiturageToUpdate);

    header("Location: crud.php"); // Redirect to the covoiturage list
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Modifier un Covoiturage</title>
  <link rel="stylesheet" href="assets/css/crud.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="container">
    <h2>Modifier un Covoiturage</h2>
    <form method="POST">
      <div class="form-group">
        <label for="lieu_depart">Lieu de départ:</label>
        <input type="text" name="lieu_depart" id="lieu_depart" required
               value="<?php echo htmlspecialchars($covoiturage['lieu_depart']); ?>">
      </div>

      <div class="form-group">
        <label for="lieu_arrivee">Lieu d'arrivée:</label>
        <input type="text" name="lieu_arrivee" id="lieu_arrivee" required
               value="<?php echo htmlspecialchars($covoiturage['lieu_arrivee']); ?>">
      </div>

      <div class="form-group">
        <label for="date_depart">Date de départ:</label>
        <input type="date" name="date_depart" id="date_depart" required
               value="<?php echo htmlspecialchars($covoiturage['date_depart']); ?>">
      </div>

      <div class="form-group">
        <label for="temps_depart">Heure de départ:</label>
        <input type="time" name="temps_depart" id="temps_depart" required
               value="<?php echo htmlspecialchars($covoiturage['temps_depart']); ?>">
      </div>

      <div class="form-group">
        <label for="places_dispo">Places disponibles:</label>
        <input type="number" name="places_dispo" id="places_dispo" min="1" max="8" required
               value="<?php echo htmlspecialchars($covoiturage['places_dispo']); ?>">
      </div>

      <div class="form-group">
        <label for="prix">Prix par place (TND):</label>
        <input type="number" name="prix" id="prix" min="1" step="0.5" required
               value="<?php echo htmlspecialchars($covoiturage['prix']); ?>">
      </div>

      <div class="form-group">
        <label for="accepte_colis">Accepte les colis:</label>
        <select name="accepte_colis" id="accepte_colis" required>
          <option value="1" <?php if ($covoiturage['accepte_colis'] == 1) echo 'selected'; ?>>Oui</option>
          <option value="0" <?php if ($covoiturage['accepte_colis'] == 0) echo 'selected'; ?>>Non</option>
        </select>
      </div>

      <div class="form-group">
        <label for="details">Détails:</label>
        <textarea name="details" id="details" rows="3"><?php echo htmlspecialchars($covoiturage['details']); ?></textarea>
      </div>

      <div class="form-actions">
        <a href="crud.php" class="btn secondary">Annuler</a>
        <button type="submit" class="btn primary">Mettre à jour</button>
      </div>
    </form>
  </div>
</body>
</html>