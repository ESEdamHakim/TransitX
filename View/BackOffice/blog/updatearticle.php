<?php 
require_once __DIR__ . '/../../../Controller/BackOffice/ArticleC.php';
require_once __DIR__ . '/../../../Model/BackOffice/Article.php';

if (isset($_GET['id'])) {
    $id_article = $_GET['id'];
    $articc = new ArticleC();  
    $offer = $articc->getOfferById($id_article);

    if ($offer) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $date_publication = $_POST['date_publication'];
            $photo = $_FILES['photo'];
            $auteur = $_POST['auteur'];
            $categorie = $_POST['categorie'];
            $tags = $_POST['tags'];


            if ($photo['error'] == 0) {
                $photo_name = time() . '_' . basename($photo['name']);
                $photo_path = '../../../uploads/' . $photo_name;
                move_uploaded_file($photo['tmp_name'], $photo_path);
            } else {
                $photo_name = $offer['photo'];
            }

            $updatedOffer = new Article($titre, $contenu, $date_publication, $photo_name, $auteur, $categorie, $tags, $id_article);
            $articc->updatearticle($updatedOffer);

            header("Location: crud.php");
            exit();
        }
    } else {
        echo "L'offre n'a pas été trouvée.";
    }
} else {
    echo "ID de l'offre manquant.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un article</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 2rem;
        }

        /* Style de la barre de navigation */
        .sidebar {
            background-color: #1f4f65;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1rem;
            color: #fff;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 30px;
            margin-right: 10px;
        }

        .sidebar-menu {
            margin-top: 2rem;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            margin-bottom: 1.5rem;
        }

        .sidebar-menu a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1.2rem; /* Augmenter la taille de la police */
        }

        .sidebar-menu a:hover {
            color: #f5f5f5;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            color: #fff;
            font-size: 1rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        /* Style de la carte de mise à jour de l'article */
        .update-form-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 2rem auto;
        }

        .update-form-card h2 {
            margin-bottom: 1.5rem;
            color: #1f4f65;
            font-size: 1.8rem; /* Augmenter la taille du titre */
            text-align: center;
        }

        label {
            display: block;
            margin: 1.5rem 0 0.5rem; /* Augmenter l'espacement entre les éléments */
            font-weight: 500;
            font-size: 1.1rem; /* Augmenter la taille de la police */
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1.1rem; /* Augmenter la taille de la police */
        }

        textarea.input-field {
            resize: vertical;
        }

        .image-preview {
            margin-top: 0.5rem;
        }

        .image-preview img {
            width: 120px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-update {
            margin-top: 1.5rem;
            background-color: #1f4f65;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem; /* Augmenter la taille de la police */
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s ease;
        }

        .btn-update:hover {
            background-color: #143645;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
                <span>Transit</span><span class="highlight">X</span>
            </div>
            <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
        </div>
        <div class="sidebar-content">
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li><a href="../colis/crud.php"><i class="fas fa-box"></i><span>Colis</span></a></li>
                    <li><a href="../blog/crud.php"><i class="fas fa-blog"></i><span>Blog</span></a></li>
                    <li><a href="../reclamations/crud.php"><i class="fas fa-exclamation-circle"></i><span>Réclamations</span></a></li>
                    <li><a href="../users/crud.php"><i class="fas fa-users"></i><span>Utilisateurs</span></a></li>
                    <li><a href="../covoiturage/crud.php"><i class="fas fa-car-side"></i><span>Covoiturage</span></a></li>
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="update-form-card">
            <h2><i class="fas fa-edit"></i> Modifier l'article</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="titre">Titre:</label>
                <input type="text" name="titre" id="titre" class="input-field" value="<?= htmlspecialchars($offer['titre']) ?>" required>

                <label for="contenu">Contenu:</label>
                <textarea name="contenu" id="contenu" class="input-field" rows="5" required><?= htmlspecialchars($offer['contenu']) ?></textarea>

                <label for="date_publication">Date de publication:</label>
                <input type="date" name="date_publication" id="date_publication" class="input-field" value="<?= htmlspecialchars($offer['date_publication']) ?>" required>
 
                <label for="auteur">auteur:</label>
                <input type="text" name="auteur" id="auteur" class="input-field" value="<?= htmlspecialchars($offer['auteur']) ?>" required>
 
                <label for="categorie">Catégorie :</label>
<select name="categorie" id="categorie" class="input-field" required>
    <option value="Conseils de voyage" <?= $offer['categorie'] === 'Conseils de voyage' ? 'selected' : '' ?>>Conseils de voyage</option>
    <option value="Sécurité" <?= $offer['categorie'] === 'Sécurité' ? 'selected' : '' ?>>Sécurité</option>
    <option value="Économie et écologie" <?= $offer['categorie'] === 'Économie et écologie' ? 'selected' : '' ?>>Économie et écologies</option>
    <option value="Autre" <?= $offer['categorie'] === 'Autre' ? 'selected' : '' ?>>Autre</option>
</select>
<label>Tags (séparés par des virgules) :</label>
            <input type="text" name="tags" id="tags" class="input-field" value="<?= htmlspecialchars($offer['tags']) ?>" placeholder="Exemple : Mobilité, Innovation, Écologie">
                <label>Photo actuelle:</label>
                <div class="image-preview">
                    <?php if (!empty($offer['photo'])): ?>
                        <img src="../../../uploads/<?= htmlspecialchars($offer['photo']); ?>" alt="Photo Article">
                    <?php else: ?>
                        <p>Aucune photo actuelle</p>
                    <?php endif; ?>
                </div>

                <label for="photo">Nouvelle photo (facultatif):</label>
                <input type="file" name="photo" id="photo" class="input-field" accept="image/*">

                <button type="submit" class="btn-update">
                    <i class="fas fa-sync-alt"></i> Mettre à jour l'article
                </button>
            </form>
        </div>
    </main>

</body>
</html>
