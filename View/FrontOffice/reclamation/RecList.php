<?php
require_once __DIR__ . '/../../../Controller/ReclamationController.php';
require_once __DIR__ . '/../../../Controller/userC.php';

session_start(); // Important : Démarrer la session en haut du fichier

$userController = new UserC();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// For testing - use the first user from the list instead of session user
// Comment this out once testing is complete
$currentUser = null;
$currentUser = null;

if (isset($_SESSION['user_id'])) {
  $currentUser = $userController->showUser($_SESSION['user_id']);
}


$ReclamationC = new ReclamationController();
$list = $ReclamationC->listReclamation();
$covoiturages = $ReclamationC->getAllCovoiturages();
$clients = $ReclamationC->getAllClients();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Mes Réclamations</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/reclamation.css">
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
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
    <?php include '../../assets/chatbot/chatbot.php'; ?>
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
                    <li><a href="../colis/index.php">Colis</a></li>
                    <li><a href="../covoiturage/index.php">Covoiturage</a></li>
                    <li><a href="../blog/index.php">Blog</a></li>
                    <li class="active"><a href="../reclamation/index.php">Réclamation</a></li>
                    <li><a href="../vehicule/index.php">Véhicule</a></li>

                </ul>
            </nav>
            <div class="header-right">
                <div class="actions">
                    <div class="actions-container">
                        <?php include '../assets/php/profile.php'; ?>
                    </div>
                    <button class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="colis-dashboard">
            <div class="container">
                <div class="dashboard-header">
                    <div class="dashboard-title">
                        <h1>Mes Réclamations</h1>
                        <p>Gérez et suivez tous vos réclamations</p>
                    </div>
                    <div class="dashboard-actions">
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau réclamation
                        </a>
                    </div>
                </div>

                <div class="filters-section">
                    <div class="filters-title">
                        <h3>Filtres</h3>
                        <button class="filters-toggle">
                            <i class="fas fa-sliders-h"></i> Afficher les filtres
                        </button>
                    </div>
                    <div class="filters-content">
                        <div class="filter-group">
                            <label for="objet-filter">Objet</label>
                            <select id="objet-filter">
                                <option value="all">Toutes les Réclamations</option>
                                <option value="retard">Retard</option>
                                <option value="annulation">Annulation</option>
                                <option value="dommage">Dommage</option>
                                <option value="qualite_service">Qualité de service</option>
                                <option value="facturation">Facturation</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="date-filter">Date de réclamation</label>
                            <input type="date" id="date-filter">
                        </div>
                        <div class="filter-group">
                            <label for="search-filter">Recherche</label>
                            <input type="text" id="search-filter" placeholder="ID Covoiturage">
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="button" class="btn btn-outline reset-btn">Réinitialiser</button>
                        <button type="button" class="btn btn-primary apply-btn">Appliquer</button>
                    </div>
                </div>

                <div class="tabs-container">
                    <div class="tab active" data-status="all">Tous</div>
                    <div class="tab" data-status="refused">Refusée</div>
                    <div class="tab" data-status="pending">En attente</div>
                    <div class="tab" data-status="in-progress">En cours</div>
                    <div class="tab" data-status="resolved">Résolue</div>
                </div>

                <div class="rec-container table-view active">
                    <div class="rec-table-container">
                        <table class="rec-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Objet</th>
                                    <th>Date</th>
                                    <th>Covoiturage</th>
                                    <th>Description</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $rec): ?>
                                    <?php
                                    $covoit = $ReclamationC->getCovoiturageById($rec['id_covoit']);
                                    if ($rec['id_client'] != $_SESSION['user_id'])
                                        continue; ?>
                                    <tr>
                                        <td><?= $rec['id_rec'] ?></td>
                                        <td><?= htmlspecialchars($rec['objet']) ?></td>
                                        <td><?= htmlspecialchars($rec['date_rec']) ?></td>
                                        <td>
                                            <?= htmlspecialchars($covoit['lieu_depart']) ?> →
                                            <?= htmlspecialchars($covoit['lieu_arrivee']) ?>
                                            (ID: <?= htmlspecialchars($covoit['id_covoit']) ?>)
                                        </td>
                                        <td>
                                            <?= strlen($rec['description']) > 50 ? htmlspecialchars(substr($rec['description'], 0, 50)) . '...' : htmlspecialchars($rec['description']) ?>
                                        </td>
                                        <?php
                                        $statusClassMap = [
                                            'En attente' => 'pending',
                                            'En cours' => 'in-progress',
                                            'Résolue' => 'resolved',
                                            'Rejetée' => 'refused'
                                        ];

                                        $statut = trim($rec['statut']);
                                        $className = isset($statusClassMap[$statut]) ? $statusClassMap[$statut] : 'default';
                                        ?>
                                        <td>
                                            <span class="status <?= $className ?>">
                                                <?= htmlspecialchars($rec['statut']) ?>
                                            </span>
                                        </td>
                                        <td class="actions">
                                            <!-- View Button -->
                                            <button class="action-btn view" data-id="<?= $rec['id_rec'] ?>"
                                                data-client="<?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>"
                                                data-objet="<?= htmlspecialchars($rec['objet']) ?>"
                                                data-date="<?= htmlspecialchars($rec['date_rec']) ?>"
                                                data-covoit="<?= htmlspecialchars($covoit['lieu_depart']) ?> → <?= htmlspecialchars($covoit['lieu_arrivee']) ?>"
                                                data-description="<?= htmlspecialchars($rec['description']) ?>"
                                                data-statut="<?= htmlspecialchars($rec['statut']) ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <form method="GET" action="updateRec.php" style="display:inline;">
                                                <input type="hidden" name="id_rec" value="<?= $rec['id_rec'] ?>">
                                                <button type="submit" class="action-btn edit" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="action-btn delete open-delete-modal"
                                                title="Supprimer" data-id="<?= htmlspecialchars($rec['id_rec']) ?>">
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
        </section>
    </main>

    <?php include '../../assets/footer.php'; ?>
    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Détails de la Réclamation</h2>
                <button class="close-modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="bus-info">
                    <p><strong>Objet:</strong> <span id="modal-objet"></span></p>
                    <p><strong>Date:</strong> <span id="modal-date"></span></p>
                    <p><strong>Covoiturage:</strong> <span id="modal-covoit"></span></p>
                    <p><strong>Description:</strong></p>
                    <p id="modal-description"></p>
                    <p><strong>Statut:</strong> <span id="modal-statut"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmer la suppression</h2>
                <button class="close-modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible.</p>
                <div class="form-actions">
                    <button type="button" class="btn secondary cancel-btn">Annuler</button>
                    <button type="button" class="btn danger" id="confirm-delete-btn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden Delete Form -->
    <form method="POST" action="deleteRec.php" style="display:none;" id="delete-form">
        <input type="hidden" name="id_rec" id="delete-id">
    </form>

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

    </script>
    <script src="assets/js/recModals.js"></script>
    <script src="assets/js/recFilters.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/chatbot.js"> </script>
    <script src="../assets/js/profile.js"></script>
</body>

</html>