<?php
require_once __DIR__ . '/../../../Controller/ArticleC.php';
require_once __DIR__ . '/../../../Model/Article.php';

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
                $photo_path = '../../assets/uploads/' . $photo_name;
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
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>TransitX - Gestion du Blog</title>
<!-- Styles -->
<link rel="stylesheet" href="../../assets/css/main.css" />
<link rel="stylesheet" href="assets/css/crud.css" />
<link rel="stylesheet" href="../assets/css/styles.css">
<link rel="stylesheet" href="assets/css/styles.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />

<style>
    .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        margin-top: 0.5rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-actions {
        margin-top: 1.5rem;
    }

    .form-group select {
        text-align: left;
        direction: ltr;
    }
</style>

<body>

    <div class="dashboard">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <section class="bus-form-section">
                <div class="container">
                    <div class="section-header">
                        <h2>
                            <i class="fas fa-edit"></i> Modifier l'article
                            <p>Modifiez les champs ci-dessous pour mettre à jour l'article.</p>
                        </h2>
                    </div>

                    <div class="bus-form-container">
                        <form class="bus-form" id="articleForm" method="POST" enctype="multipart/form-data">
                            <div class="form-grid">
                                <!-- Left Column -->
                                <div class="form-column">
                                    <div class="form-group">
                                        <label for="titre">Titre :</label>
                                        <input type="text" id="titre" name="titre" class="form-input"
                                            value="<?= htmlspecialchars($offer['titre']) ?>" required />
                                        <span class="error-message" id="titre-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="contenu">Contenu :</label>
                                        <textarea id="contenu" name="contenu" class="form-textarea" rows="4"
                                            required><?= htmlspecialchars($offer['contenu']) ?></textarea>
                                        <span class="error-message" id="contenu-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="date_publication">Date de Publication :</label>
                                        <input type="date" id="date_publication" name="date_publication"
                                            class="form-input"
                                            value="<?= htmlspecialchars($offer['date_publication']) ?>" required />
                                        <span class="error-message" id="date-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="categorie">Catégorie :</label>
                                        <select id="categorie" name="categorie" class="form-input" required>
                                            <option value="" disabled>-- Sélectionnez une catégorie --</option>
                                            <option value="Conseils de voyage" <?= $offer['categorie'] === 'Conseils de voyage' ? 'selected' : '' ?>>
                                                Conseils de voyage
                                            </option>
                                            <option value="Sécurité" <?= $offer['categorie'] === 'Sécurité' ? 'selected' : '' ?>>
                                                Sécurité
                                            </option>
                                            <option value="Économie et écologie" <?= $offer['categorie'] === 'Économie et écologie' ? 'selected' : '' ?>>
                                                Économie et écologie
                                            </option>
                                            <option value="Autre" <?= $offer['categorie'] === 'Autre' ? 'selected' : '' ?>>
                                                Autre
                                            </option>
                                        </select>
                                        <span class="error-message" id="categorie-error"></span>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="form-column">
                                    <div class="form-group">
                                        <label for="auteur">Auteur :</label>
                                        <input type="text" id="auteur" name="auteur" class="form-input"
                                            value="<?= htmlspecialchars($offer['auteur']) ?>" required />
                                        <span class="error-message" id="auteur-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="tags">Tags (séparés par des virgules) :</label>
                                        <input type="text" id="tags" name="tags" class="form-input"
                                            placeholder="Exemple : Mobilité, Innovation"
                                            value="<?= htmlspecialchars($offer['tags']) ?>" />
                                        <span class="error-message" id="tags-error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Photo actuelle :</label>
                                        <div class="image-preview" style="margin-bottom: 10px;">
                                            <?php if (!empty($offer['photo'])): ?>
                                                <img src="../../assets/uploads/<?= htmlspecialchars($offer['photo']); ?>"
                                                    alt="Photo Article"
                                                    style="max-width: 100%; height: auto; border-radius: 5px;" />
                                            <?php else: ?>
                                                <p>Aucune photo actuelle</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="photo">Nouvelle photo (facultatif) :</label>
                                        <input type="file" id="photo" name="photo" accept="image/*"
                                            class="form-input" />
                                        <span class="error-message" id="photo-error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions text-center" style="margin-top: 20px;">
                                <a href="crud.php" class="btn cancel" style="margin-right: 10px;">
                                    Annuler <i class="fas fa-times"></i>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Mettre à jour l'article <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>


</body>

</html>