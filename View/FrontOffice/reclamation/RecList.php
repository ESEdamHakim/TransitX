<?php
require_once __DIR__ . '/../../../Controller/ReclamationController.php';

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
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/reclamation.css">
    <link rel="stylesheet" href="../../BackOffice/bus/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
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

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-size: 16px;
    }

    .btn.primary {
        background-color: var(--primary);
        color: white;
    }

    .btn.primary:hover {
        background-color: #86b391;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(151, 195, 162, 0.4);
    }

    .btn.secondary {
        background-color: white;
        border: 1px solid #ddd;
        color: #333;
    }

    .btn.secondary:hover {
        background-color: #f8f9fa;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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

    .tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab:hover {
        color: var(--primary);
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

    .colis-table-container {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .colis-table {
        width: 100%;
        border-collapse: collapse;
    }

    .colis-table th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 600;
        text-align: left;
        padding: 1rem;
        border-bottom: 1px solid #ddd;
    }

    .colis-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f1f1;
        color: #333;
    }

    .colis-table tr:last-child td {
        border-bottom: none;
    }

    .colis-table tr:hover {
        background-color: #f8f9fa;
    }

    .status {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status.pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .status.in-transit {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .status.delivered {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .status.cancelled {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        transition: all 0.3s;
        background-color: transparent;
    }

    .action-btn:hover {
        background-color: #f1f1f1;
    }

    .action-btn.view:hover {
        color: var(--secondary);
    }

    .action-btn.edit:hover {
        color: var(--primary);
    }

    .action-btn.delete:hover {
        color: #dc3545;
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

    @media (max-width: 768px) {
        .colis-table-container {
            overflow-x: auto;
        }

        .colis-table {
            min-width: 800px;
        }
    }
</style>
</head>

<body>
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
                    <li><a href="index.php">Colis</a></li>
                    <li><a href="../covoiturage/index.php">Covoiturage</a></li>
                    <li><a href="../blog/index.php">Blog</a></li>
                    <li class="active"><a href="../reclamation/index.php">Réclamation</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <a href="../../BackOffice/index.php" class="btn btn-outline btn">Dashboard</a>
                <a href="../../../index.php" class="btn primary">Déconnexion</a>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
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
                        <a href="index.php" class="btn primary">
                            <i class="fas fa-plus"></i> Nouveau réclamation
                        </a>
                        <button class="btn secondary">
                            <i class="fas fa-download"></i> Exporter
                        </button>
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
                            <label for="status-filter">Statut</label>
                            <select id="status-filter">
                                <option value="all">Tous les statuts</option>
                                <option value="en_cours">En cours</option>
                                <option value="traitee">Traitée</option>
                                <option value="rejettee">Rejetée</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="date-filter">Date de réclamation</label>
                            <input type="date" id="date-filter">
                        </div>
                        <div class="filter-group">
                            <label for="search-filter">Recherche</label>
                            <input type="text" id="search-filter" placeholder="ID réclamation, objet...">
                        </div>
                        <div class="filter-group">
                            <label for="search-filter">Recherche</label>
                            <input type="text" id="search-filter" placeholder="ID, destinataire...">
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button class="btn secondary">Réinitialiser</button>
                        <button class="btn primary">Appliquer</button>
                    </div>
                </div>

                <div class="tabs-container">
                    <div class="tab active">Tous <span class="count">12</span></div>
                    <div class="tab">En attente <span class="count">3</span></div>
                    <div class="tab">En transit <span class="count">5</span></div>
                    <div class="tab">Livrés <span class="count">4</span></div>
                </div>

                <div style="overflow-x: auto; max-width: 100%;">
                    <table class="colis-table">
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
                                if ($rec['id_client'] != 3)
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
                                    <td><?= htmlspecialchars($rec['description']) ?></td>
                                    <td><?= htmlspecialchars($rec['statut']) ?></td>
                                    <td class="actions">
                                        <form method="GET" action="updateRec.php" style="display:inline;">
                                            <input type="hidden" name="id_rec" value="<?= $rec['id_rec'] ?>">
                                            <button type="submit" class="action-btn edit" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="deleteRec.php" style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this reclamation?');">
                                            <input type="hidden" name="id_rec" value="<?= $rec['id_rec'] ?>">
                                            <button type="submit" class="action-btn delete" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>


            </div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-logo">
                    <img src="../../assets/images/logo.png" alt="TransitX Logo" class="footer-logo-img">
                    <span>TransitX</span>
                </div>
                <div class="footer-slogan">
                    <p>Move Green, Live Clean</p>
                </div>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-middle">
                <div class="footer-column">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="../bus/index.php">Bus</a></li>
                        <li><a href="../covoiturage/index.php">Covoiturage</a></li>
                        <li><a href="index.php">Colis</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>À propos</h4>
                    <ul>
                        <li><a href="../about.php">Notre mission</a></li>
                        <li><a href="../blog/index.php">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Légal</h4>
                    <ul>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Avenue Habib Bourguiba, Tunis</li>
                        <li><i class="fas fa-phone"></i> +216 71 123 456</li>
                        <li><i class="fas fa-envelope"></i> contact@transitx.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TransitX. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

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
</body>

</html>