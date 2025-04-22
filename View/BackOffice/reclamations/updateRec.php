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
    <title>TransitX - Ajouter une Réclamation</title>

    <!-- CSS Imports -->
    <link rel="stylesheet" href="assets/css/reclamation.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/main.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

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
                        <li>
                            <a href="../bus/crud.php">
                                <i class="fas fa-bus"></i>
                                <span>Bus</span>
                            </a>
                        </li>
                        <li>
                            <a href="crud.php">
                                <i class="fas fa-box"></i>
                                <span>Colis</span>
                            </a>
                        </li>
                        <li class="active">
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

            <section class="section">
                <div class="container">
                    <div class="section-header">
                        <br>
                        <h2>Modifier une réclamation</h2>
                        <p>Modifiez les informations ci-dessous</p>
                    </div>

                    <div class="container">
                        <form class="reclamation-form" method="POST">

                            <div class="form-section">
                                <h3 class="form-title">Détails de la réclamation</h3>

                                <div class="form-group">
                                    <label for="id_client">ID Client</label>
                                    <input type="number" name="id_client" id="id_client"
                                        value="<?php echo htmlspecialchars($reclamation['id_client']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="objet">Objet de la réclamation</label>
                                    <select name="objet" id="objet" required>
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
                                        <label for="id_covoit">ID du Covoiturage</label>
                                        <input type="number" name="id_covoit" id="id_covoit" min="1"
                                            placeholder="Entrez l’ID"
                                            value="<?php echo htmlspecialchars($reclamation['id_covoit']); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description détaillée</label>
                                    <textarea name="description" id="description" rows="5"
                                        placeholder="Expliquez votre situation..."
                                        required><?php echo htmlspecialchars($reclamation['description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="statut">Statut</label>
                                    <select name="statut" id="statut" required>
                                        <option value="">-- Sélectionner un statut --</option>
                                        <option value="En attente" <?php if ($reclamation['statut'] == 'En attente')
                                            echo 'selected'; ?>>En attente</option>
                                        <option value="En cours de traitement" <?php if ($reclamation['statut'] == 'En cours de traitement')
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
                                    <a href="RecList.php" class="btn btn-secondary">
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

                // Filter colis based on tab (for a real application, this would use AJAX to fetch filtered data)
                const tabName = this.getAttribute('data-tab');
                console.log(`Switching to tab: ${tabName}`);
            });
        });
    </script>
</body>

</html>