<?php
try {
    require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
} catch (Exception $e) {
    echo 'Erreur lors de l\'inclusion du fichier : ' . $e->getMessage();
}

$articc = new ArticleC();

// Vérifier si un terme de recherche a été soumis
if (isset($_GET['searchTerm']) && !empty($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    // Utilisation de la méthode searchArticles pour effectuer la recherche
    $list = $articc->searchArticles($searchTerm);
} else {
    // Si aucune recherche n'a été effectuée, afficher tous les articles
    $list = $articc->listarticle();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestion du Blog</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .search-form {
            margin-bottom: 20px;
        }
        .buses-table-container {
            margin-top: 30px;
            overflow-x: auto;
        }
        .buses-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        .buses-table thead {
            background-color: #1f4f65;
            color: #fff;
        }
        .buses-table th, .buses-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .buses-table tr:hover {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
        .action-btn {
            border: none;
            background: none;
            cursor: pointer;
            color: #333;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        .action-btn.edit {
            color: #007bff;
        }
        .action-btn.delete {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Formulaire de recherche -->
    <form action="" method="GET" class="search-form">
        <label for="searchTerm">Rechercher un article par titre :</label>
        <input type="text" id="searchTerm" name="searchTerm" placeholder="Entrez le titre de l'article" value="<?= isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : ''; ?>" required>
        <button type="submit">Rechercher</button>
    </form>

    <!-- Tableau d'affichage des articles -->
    <div class="buses-table-container">
        <table class="buses-table">
            <thead>
                <tr>
                    <th>id_article</th>
                    <th>titre</th>
                    <th>contenu</th>
                    <th>date_publication</th>
                    <th>categorie</th>
                    <th>tags</th>
                    <th>photo</th>
                    <th>Actions</th>  
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($list)) {
                    echo "<tr><td colspan='6'>Aucun article trouvé.</td></tr>";
                } else {
                    foreach ($list as $offer) {
                ?>
                        <tr>
                            <td><?= htmlspecialchars($offer['id_article']); ?></td>
                            <td><?= htmlspecialchars($offer['titre']); ?></td>
                            <td><?= htmlspecialchars($offer['contenu']); ?></td>
                            <td><?= htmlspecialchars($offer['date_publication']); ?></td>
                            <td><?= htmlspecialchars($offer['categorie']); ?></td>
                            <td><?= htmlspecialchars($offer['tags']); ?></td>


                            <td>
                                <?php if (!empty($offer['photo'])): ?>
                                    <img src="../../../uploads/<?= htmlspecialchars($offer['photo']); ?>" alt="Photo Article" style="width: 80px;">
                                <?php else: ?>
                                    Aucune image
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="updatearticle.php?id=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="deletearticle.php?id_article=<?= htmlspecialchars($offer['id_article']); ?>" class="action-btn delete" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
