<?php
session_start(); // Important : Démarrer la session en haut du fichier
$isLoggedIn = isset($_SESSION['user_id']);

require_once __DIR__ . '/../../assets/fpdf186/fpdf.php';

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "<p>Vous devez être connecté pour accéder à cette page.</p>";
    exit;
}

// Récupérer l'ID de l'utilisateur connecté depuis la session
$connectedUserId = $_SESSION['user_id'];

$userCheckStmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE id = ?");
$userCheckStmt->execute([$connectedUserId]);


$userExists = $userCheckStmt->fetchColumn() > 0;

if (!$userExists) {
    echo "<p>Utilisateur non reconnu.</p>";
    exit;
}

// Récupérer l'ID de l'article depuis l'URL
$id_article = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les données de l'article
$stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->execute([$id_article]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'article existe
if (!$article) {
    echo "<p>Article introuvable.</p>";
    exit;
}

// Récupérer les commentaires principaux (ceux sans parent)
$commentStmt = $pdo->prepare("
SELECT c.*, u.nom, u.prenom
    FROM commentaire c
    JOIN user u ON c.id_user = u.id
    WHERE c.id_article = ? AND c.id_parent IS NULL 
    ORDER BY c.epingle DESC, c.date_commentaire DESC
");

$commentStmt->execute([$id_article]);
$commentaires = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour récupérer les réponses à un commentaire
function getReplies($pdo, $parentId)
{
    $stmt = $pdo->prepare("
        SELECT c.*, u.nom AS nom_auteur, u.prenom AS prenom_auteur, 
                      up.nom AS nom_parent, up.prenom AS prenom_parent
        FROM commentaire c
        LEFT JOIN user u ON c.id_user = u.id
        LEFT JOIN commentaire cp ON c.id_parent = cp.id_commentaire
        LEFT JOIN user up ON cp.id_user = up.id
        WHERE c.id_parent = ? 
        ORDER BY c.date_commentaire ASC
    ");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($article['titre']); ?> - TransitX</title>
    <!-- Inclure le CSS de Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

    <!-- Inclure la bibliothèque Quill -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <style>
        .language-selector {
            position: absolute;
            right: 20px;
        }


        .blog-detail {
            width: 70%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .blog-detail h2 {
            font-size: 36px;
            color: #1f4f65;
            text-align: center;
            margin-bottom: 20px;
        }

        .post-info {
            font-size: 16px;
            color: #888;
            text-align: center;
            margin-bottom: 40px;
        }

        .post-info small {
            color: #555;
        }

        .content {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
        }

        .content h3 {
            color: #86b391;
            font-size: 28px;
            margin-top: 40px;
        }

        .content p {
            margin-bottom: 25px;
        }

        .comment-section {
            margin: 40px auto;
            width: 70%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .comment-section h3 {
            font-size: 24px;
            color: #1f4f65;
            margin-bottom: 15px;
        }

        .comment-section form textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        .comment-section form button {
            background-color: #97c3a2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
        }

        .comment-section form button:hover {
            background-color: #1f4f65;
            color: white;
        }

        .comments {
            margin-top: 40px;
        }

        .comment {
            background-color: #f1f1f1;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
        }

        .comment p {
            margin: 5px 0;
        }

        .social-share {
            text-align: center;
            margin-top: 30px;
        }

        .social-share a {
            text-decoration: none;
            margin: 0 15px;
            font-size: 30px;
            color: #333;
            transition: color 0.3s ease;
        }

        .social-share a:hover {
            color: #86b391;
        }

        .social-share a i.fa-file-pdf:hover {
            color: #9a0007;
        }

        #translateButton {
            background-color: #7f8c8d;
            /* Gris doux et moderne */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #translateButton:hover {
            background-color: #636e72;
            /* Gris un peu plus foncé au survol */
        }


        #translateButton:disabled {
            background-color: #e0e0e0;
            border-color: #ccc;
            color: #888;
            cursor: not-allowed;
            transform: none;
        }

        #translateButton i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        #translateButton:hover i {
            transform: translateX(5px);
        }

        .fas.fa-thumbtack {
            font-size: 20px;
            /* Taille de l'icône */
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .fas.fa-thumbtack:hover {
            color: #4CAF50;
            /* Couleur au survol */
            transform: scale(1.1);
            /* Agrandissement léger au survol */
        }

        .article-meta-right {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .article-meta-right small {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        #languageButton {
            background-color: white;
            color: #444;
            border: 1px solid #ccc;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #languageButton:hover {
            background-color: #f9f9f9;
        }

        #languageDropdown {
            display: none;
            position: absolute;
            top: 100%;
            /* aligne le dropdown sous le bouton */
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            min-width: 200px;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .btn-speak {
            background-color: white;
            /* Fond blanc */
            color: black;
            /* Texte noir */
            border: 1px solid #ccc;
            /* Bordure grise */
            padding: 8px 16px;
            /* Réduit le padding */
            border-radius: 6px;
            /* Réduit les coins arrondis */
            font-size: 14px;
            /* Réduit la taille de la police */
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            /* Réduit l'espacement entre l'icône et le texte */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-speak i {
            color: black;
            /* Icône noire */
            font-size: 16px;
            /* Réduit la taille de l'icône */
        }

        .btn-speak:hover {
            background-color: #f9f9f9;
            /* Fond gris clair au survol */
            color: #333;
            /* Texte gris foncé */
        }

        .btn-speak:disabled {
            background-color: #e0e0e0;
            /* Fond gris clair pour état désactivé */
            color: #888;
            /* Texte gris clair */
            cursor: not-allowed;
        }

        .comment-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comment-actions form {
            display: inline-block;
            margin: 0;
        }

        .comment-actions a {
            display: inline-block;
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
                    <li><a href="../colis/index.php">Colis</a></li>
                    <li><a href="../covoiturage/index.php">Covoiturage</a></li>
                    <li class="active"><a href="index.php">Blog</a></li>
                    <li><a href="../reclamation/index.php">Réclamation</a></li>
                    <li><a href="../vehicule/index.php">Véhicule</a></li>

                </ul>
            </nav>
            <div class="header-right">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
                    <a href="../../BackOffice/index.php" class="btn btn-outline">Dashboard</a>
                <?php endif; ?>
                <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
            </div>

            <div class="language-selector">
                <!-- Icône avec un bouton de traduction -->
                <button id="languageButton" onclick="toggleLanguageDropdown()">
                    <i class="fa fa-language"></i> <span></span>
                </button>

                <!-- Liste déroulante cachée par défaut -->
                <div id="languageDropdown"
                    style="display: none; position: absolute; background-color: white; padding: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
                    <select id="languageSelect">
                        <option value="fr|fr">Français</option>
                        <option value="fr|en">Anglais</option>
                        <option value="fr|es">Espagnol</option>
                        <option value="fr|de">Allemand</option>
                        <option value="fr|it">Italien</option>
                        <option value="fr|pt">Portugais</option>
                    </select>

                    <!-- Bouton Traduire -->
                    <button id="translateButton" onclick="translateArticle()" style="margin-right: 15px;">
                        <i class="fa fa-check"></i> Traduire cet article
                    </button>

                </div>
                <div style="display: flex; gap: 10px; align-items: center; justify-content: center; margin-top: 5px;">
                    <button class="btn-speak" data-content="<?php echo htmlspecialchars($article['contenu']); ?>"
                        title="Écouter l'article">
                        <i class="fa fa-headphones"></i>
                    </button>
                </div>


            </div>
        </div>
    </header>

    <script>
        function toggleLanguageDropdown() {
            var dropdown = document.getElementById("languageDropdown");
            // Toggle affichage de la liste déroulante des langues
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        function translateArticle() {
            var selectedLanguage = document.getElementById("languageSelect").value;
            alert("Traduction en : " + selectedLanguage);  // Cette fonction peut être étendue pour effectuer la traduction
        }
    </script>




    <div class="blog-detail">
        <div class="article-meta-right">
            <small><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($article['date_publication']); ?></small>
            <small><i class="fa fa-user"></i> <?php echo htmlspecialchars($article['auteur']); ?></small>
            <small><i class="fa fa-tag"></i> <?php echo htmlspecialchars($article['categorie']); ?></small>
        </div>
        <h2 id="articleTitle"><?php echo htmlspecialchars($article['titre']); ?></h2>


        <div class="post-info">
            <p>


            <div style="text-align: center; margin-top: 30px;">



            </div>
            </p>
        </div>



        <div class="content">
            <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>


            <h3>Les Options de Trajets Flexibles</h3>
            <p>Les trajets flexibles permettent de choisir une option qui s'adapte à votre emploi du temps et à vos
                besoins...</p>

            <h3>Les Bénéfices Écologiques et Économiques</h3>
            <p>En réduisant le nombre de voitures sur les routes, TransitX contribue à diminuer l'empreinte carbone...
            </p>

            <h3>Des Trajets Plus Efficaces Grâce à la Technologie</h3>
            <p>Grâce à l'intelligence artificielle et aux données de trajets, TransitX optimise les itinéraires...</p>
        </div>


        <!-- Lien PDF dans le même style -->
        <div class="social-share">
            <p>Obtenez une version PDF de l'article :</p>

            <!-- Lien PDF -->
            <a href="generate_pdf.php?id=<?php echo $id_article; ?>" target="_blank"
                title="Télécharger le PDF de l'article">
                <i class="fas fa-file-pdf" style="color: #D32F2F;"></i>
            </a>
        </div>



    </div>

    <div class="comment-section">
        <div class="comments">
            <h3>Commentaires</h3>
            <?php if (count($commentaires) === 0): ?>
                <p>Pas encore de commentaires.</p>
            <?php else: ?>
                <?php foreach ($commentaires as $commentaire): ?>
                    <div class="comment" style="margin-bottom: 20px; padding: 15px; border-bottom: 1px solid #eee;">
                        <!-- Affiche l'email de l'utilisateur et bouton épingler/désépingler -->
                        <div class="comment-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex-grow: 1;">
                                <p style="display: inline; margin: 0; font-weight: bold; color: #333;">
                                    <?php echo htmlspecialchars($commentaire['nom']); ?>
                                </p>
                                <p style="display: inline; margin: 0; font-weight: bold; color: #333;">
                                    <?php echo htmlspecialchars($commentaire['prenom']); ?>
                                </p>


                                <div class="comment-date" style="font-size: 0.9em; color: #888;">
                                    <?php echo htmlspecialchars($commentaire['date_commentaire']); ?>
                                </div>
                            </div>
                            <!-- Bouton Épingler/Désépingler avec icônes -->
                            <?php if ($commentaire['epingle'] == 0): ?>
                                <a href="epingle_commentaire.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=epingle"
                                    title="Épingler ce commentaire" style="color: #007bff;">
                                    <i class="fas fa-thumbtack"></i>
                                </a>
                            <?php else: ?>
                                <a href="epingle_commentaire.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=desepingle"
                                    title="Désépingler ce commentaire" style="color: #dc3545;">
                                    <i class="fas fa-thumbtack"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Contenu du commentaire -->
                        <div class="comment-content" style="margin-top: 10px;">
                            <p><?php echo nl2br(htmlspecialchars($commentaire['contenu_commentaire'])); ?></p>
                        </div>

                        <!-- Like / Dislike -->
                        <div class="comment-likes" style="margin-top: 8px;">
                            <span>❤️ <?php echo $commentaire['nb_likes']; ?></span>
                            <a href="like_dislike.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=like"
                                style="margin-right: 10px; color: green;">Like</a>
                            <span>👎 <?php echo $commentaire['nb_dislikes']; ?></span>
                            <a href="like_dislike.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=dislike"
                                style="color: red;">Dislike</a>
                        </div>

                        <!-- Formulaire pour répondre à ce commentaire -->
                        <form action="process_comment.php" method="POST" class="reply-form" style="margin-top: 15px;">
                            <input type="hidden" name="id_article" value="<?php echo $article['id_article']; ?>">
                            <input type="hidden" name="id_user" value="<?php echo $connectedUserId; ?>">
                            <input type="hidden" name="parent_id" value="<?php echo $commentaire['id_commentaire']; ?>">
                            <!-- ID du commentaire parent -->
                            <textarea name="comment" rows="3" placeholder="Répondre à ce commentaire..."
                                required></textarea><br />
                            <button type="submit">Répondre</button>
                        </form>

                        <!-- Boutons Modifier et Supprimer -->
                        <div class="comment-actions" style="margin-top: 10px; display: flex; gap: 10px;">
                            <form method="get" action="modifier_commentaire.php" style="margin: 0;">
                                <input type="hidden" name="id" value="<?php echo $commentaire['id_commentaire']; ?>">
                                <button type="submit"
                                    style="background: none; border: none; color: #4CAF50; font-size: 20px; cursor: pointer;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <a href="supprimer_commentaire.php?id_commentaire=<?php echo $commentaire['id_commentaire']; ?>&id_article=<?php echo $article['id_article']; ?>"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');"
                                style="color: red; font-size: 20px;">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Affichage des réponses sous le commentaire -->
                    <?php
                    // Récupère les réponses (sous-commentaires) liées au commentaire actuel
                    $replies = getReplies($pdo, $commentaire['id_commentaire']);
                    foreach ($replies as $reply):
                        ?>
                        <div class="comment reply"
                            style="display: flex; align-items: flex-start; margin-bottom: 15px; margin-left: 30px; background-color: #f9f9f9; border-left: 3px solid #ddd;">
                            <div class="comment-content" style="flex-grow: 1;">
                                <p><?php echo nl2br(htmlspecialchars($reply['contenu_commentaire'])); ?></p>

                                <!-- Affiche l'email de l'utilisateur ayant posté la réponse -->
                                <div class="reply-email" style="font-size: 0.8em; color: gray; margin-top: 5px;">

                                </div>

                                <div class="comment-date" style="font-size: 0.8em; color: #666; margin-top: 5px;">

                                    <?php echo htmlspecialchars($reply['date_commentaire']); ?>
                                </div>
                                <p style="display: inline; margin: 0; font-size: 0.8em; color: gray;">Commenté par :
                                    <?php echo htmlspecialchars($reply['nom_auteur']); ?>
                                </p>
                                <p style="display: inline; margin: 0; font-size: 0.8em; color: gray;">
                                    <?php echo htmlspecialchars($reply['prenom_auteur']); ?>
                                </p>


                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Formulaire d'ajout de commentaire -->
        <h3>Ajouter un commentaire</h3>
        <?php if ($isLoggedIn && $userExists): ?>
            <form action="process_comment.php" method="POST">
                <input type="hidden" name="id_article" value="<?php echo $id_article; ?>">
                <input type="hidden" name="id_user" value="<?php echo $connectedUserId; ?>">
                <textarea name="comment" id="comment" rows="4" placeholder="Écrivez votre commentaire ici..."
                    required></textarea>
                <br />
                <button type="submit">Commenter</button>
            </form>
        <?php elseif ($isLoggedIn && !$userExists): ?>
            <p style="color: red;">Vous devez être connecté pour laisser un commentaire.</p>
        <?php else: ?>
            <p style="color: red;">Vous devez être connecté pour laisser un commentaire.</p>
        <?php endif; ?>
    </div>

    <?php include '../../assets/footer.php'; ?>

    <script>
        async function translateArticle() {
            const button = document.getElementById('translateButton');
            button.disabled = true;
            button.innerHTML = "Traduction en cours... <span class='spinner'></span>";

            const selectedLanguage = document.getElementById('languageSelect').value;

            const textElements = document.querySelectorAll('.content p, .content h3, .social-share p, #articleTitle');

            const translationPromises = Array.from(textElements).map(async (el) => {
                // Sauvegarder le texte original s’il n’est pas encore sauvegardé
                if (!el.dataset.original) {
                    el.dataset.original = el.innerText.trim();
                }

                const originalText = el.dataset.original;

                if (originalText.length > 0) {
                    const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(originalText)}&langpair=${selectedLanguage}&de=emnagarbaa200@gmail.com`);
                    const data = await response.json();
                    return { el, translatedText: data.responseData.translatedText };
                }

                return null;
            });

            const results = await Promise.all(translationPromises);

            results.forEach(result => {
                if (result) {
                    result.el.innerText = result.translatedText;
                }
            });

            button.innerHTML = "Article traduit ✅";
            button.disabled = false;
        }

    </script>
    <script>
        document.querySelectorAll('.btn-speak').forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.dataset.content;

                // Si une lecture est déjà en cours, l'arrêter
                if (window.speechSynthesis.speaking) {
                    window.speechSynthesis.cancel();
                    return; // Ne pas relancer
                }

                const selectedLanguage = document.getElementById('languageSelect').value;
                let langCode = 'fr-FR'; // Langue par défaut

                switch (selectedLanguage) {
                    case 'fr|en':
                        langCode = 'en-US';
                        break;
                    case 'fr|es':
                        langCode = 'es-ES';
                        break;
                    case 'fr|de':
                        langCode = 'de-DE';
                        break;
                    case 'fr|it':
                        langCode = 'it-IT';
                        break;
                    case 'fr|pt':
                        langCode = 'pt-PT';
                        break;
                    case 'fr|fr':
                    default:
                        langCode = 'fr-FR';
                }

                const utterance = new SpeechSynthesisUtterance(content);
                utterance.lang = langCode;
                window.speechSynthesis.speak(utterance);
            });
        });

    </script>

    <style>
        /* Style pour le spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            /* Gris clair */
            border-top: 3px solid #3498db;
            /* Bleu pour la rotation */
            border-radius: 50%;
            width: 15px;
            height: 15px;
            animation: spin 1s linear infinite;
        }

        /* Animation de rotation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>


</body>

</html>