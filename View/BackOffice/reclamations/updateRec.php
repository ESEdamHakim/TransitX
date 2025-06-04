<?php
require_once '../../../Controller/ReclamationController.php';

// Check if an ID is provided in the URL
if (!isset($_GET['id_rec'])) {
    die("Invalid Request");
}

$id_rec = $_GET['id_rec'];
$reclamation = null;

// Fetch the existing reclamation details
$ReclamationC = new ReclamationController();
$list = $ReclamationC->listReclamation();
foreach ($list as $r) {
    if ($r['id_rec'] == $id_rec) {
        $reclamation = $r;
        break;
    }
}

if (!$reclamation) {
    die("Reclamation not found");
}

// Fetch clients and covoiturages for dropdowns
$covoiturages = $ReclamationC->getAllCovoiturages();
$clients = $ReclamationC->getAllClients();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_covoit = !empty($_POST['id_covoit']) ? $_POST['id_covoit'] : NULL;

    $ReclamationC->updateReclamation(
        $id_rec,
        $_POST['id_client'],
        $id_covoit,
        $_POST['objet'],
        $_POST['description'],
        $_POST['date_rec'],
        $_POST['statut']
    );

    header("Location: crud.php"); // Redirect to the reclamation list
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Modifier une Réclamation</title>

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
                        <h2>Modifier une réclamation
                            <p>Modifiez les informations ci-dessous</p>
                        </h2>
                    </div>

                    <div class="container">
                        <form class="reclamation-form" method="POST">

                            <div class="form-section">
                                <h3 class="form-title">Détails de la réclamation</h3>

                                <div class="form-group">
                                    <label for="id_client">Client</label>
                                    <select name="id_client" id="id_client">
                                        <option value="">-- Sélectionner un client --</option>
                                        <?php
                                        foreach ($clients as $client) {
                                            $selected = ($client['id'] == $reclamation['id_client']) ? 'selected' : '';
                                            echo "<option value='{$client['id']}' $selected>{$client['nom']} {$client['prenom']} (ID: {$client['id']})</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="objet">Objet de la réclamation</label>
                                    <select name="objet" id="objet">
                                        <option value="">-- Sélectionner un objet --</option>
                                        <option value="Retard" <?php if ($reclamation['objet'] == 'Retard')
                                            echo 'selected'; ?>>Retard</option>
                                        <option value="Annulation" <?php if ($reclamation['objet'] == 'Annulation')
                                            echo 'selected'; ?>>Annulation</option>
                                        <option value="Dommage" <?php if ($reclamation['objet'] == 'Dommage')
                                            echo 'selected'; ?>>Dommage</option>
                                        <option value="Qualité de service" <?php if ($reclamation['objet'] == 'Qualité de service')
                                            echo 'selected'; ?>>Qualité de service</option>
                                        <option value="Facturation" <?php if ($reclamation['objet'] == 'Facturation')
                                            echo 'selected'; ?>>Facturation</option>
                                        <option value="Autre" <?php if ($reclamation['objet'] == 'Autre')
                                            echo 'selected'; ?>>Autre</option>
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="form-group half">
                                        <label for="incident-date">Date de l'incident</label>
                                        <input type="date" name="date_rec" id="incident-date"
                                            value="<?php echo htmlspecialchars($reclamation['date_rec']); ?>">
                                    </div>

                                    <div class="form-group half">
                                        <label for="id_covoit">Covoiturage</label>
                                        <select name="id_covoit" id="id_covoit">
                                            <option value="">-- Sélectionner un covoiturage --</option>
                                            <?php
                                            foreach ($covoiturages as $covoit) {
                                                $selected = ($covoit['id_covoit'] == $reclamation['id_covoit']) ? 'selected' : '';
                                                echo "<option value='{$covoit['id_covoit']}' $selected>{$covoit['lieu_depart']} → {$covoit['lieu_arrivee']} (ID: {$covoit['id_covoit']})</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description détaillée</label>
                                    <textarea name="description" id="description" rows="5"
                                        placeholder="Expliquez votre situation..."><?php echo htmlspecialchars($reclamation['description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="statut">Statut</label>
                                    <select name="statut" id="statut">
                                        <option value="">-- Sélectionner un statut --</option>
                                        <option value="En attente" <?php if ($reclamation['statut'] == 'En attente')
                                            echo 'selected'; ?>>En attente</option>
                                        <option value="En cours" <?php if ($reclamation['statut'] == 'En cours')
                                            echo 'selected'; ?>>En cours de traitement</option>
                                        <option value="Résolue" <?php if ($reclamation['statut'] == 'Résolue')
                                            echo 'selected'; ?>>Résolue</option>
                                        <option value="Rejetée" <?php if ($reclamation['statut'] == 'Rejetée')
                                            echo 'selected'; ?>>Rejetée</option>
                                    </select>
                                </div>

                                <input type="hidden" name="id_rec"
                                    value="<?php echo htmlspecialchars($reclamation['id_rec']); ?>">

                                <div class="form-actions">
                                    <a href="crud.php" class="btn btn-outline">
                                        Annuler <i class="fas fa-times"></i>
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Mettre à jour <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
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