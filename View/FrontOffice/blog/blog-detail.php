<?php
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

    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/blog.css">
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Inclure la bibliothèque Quill -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


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
    <div class="blog-detail">
        <div class="language-selector" style="position: relative;">
            <!-- Bouton de traduction -->
            <button id="languageButton" onclick="toggleLanguageDropdown()">
                <i class="fa fa-language"></i>
            </button>
            <!-- Bouton écouter -->
            <button class="btn-speak" data-content="<?php echo htmlspecialchars($article['contenu']); ?>"
                title="Écouter l'article">
                <i class="fa fa-headphones"></i>
            </button>
            <!-- Dropdown de langue -->
            <div id="languageDropdown">
                <select id="languageSelect">
                    <option value="fr|fr">Français</option>
                    <option value="fr|en">Anglais</option>
                    <option value="fr|es">Espagnol</option>
                    <option value="fr|de">Allemand</option>
                    <option value="fr|it">Italien</option>
                    <option value="fr|pt">Portugais</option>
                </select>
                <button id="translateButton" onclick="translateArticle()">
                    <i class="fa fa-check"></i> Traduire cet article
                </button>
            </div>
        </div>
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
                                    title="Épingler ce commentaire" style="color: #86b391;">
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
                        <div class="comment-content" style="margin-top: 10px;,color: #111;">
                            <p><?php echo nl2br(htmlspecialchars($commentaire['contenu_commentaire'])); ?></p>
                        </div>

                        <!-- Like / Dislike -->
                        <div class="comment-likes" style="margin-top: 8px; display: flex; align-items: center; gap: 12px;">
                            <a href="like_dislike.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=like"
                                class="like-btn" data-id="<?php echo $commentaire['id_commentaire']; ?>" data-action="like"
                                title="J'aime"
                                style="color: #86b391; font-size: 20px; display: flex; align-items: center; gap: 4px;">
                                <i class="fas fa-thumbs-up"></i>
                                <span style="font-size: 16px;"><?php echo $commentaire['nb_likes']; ?></span>
                            </a>
                            <a href="like_dislike.php?id=<?php echo $commentaire['id_commentaire']; ?>&action=dislike"
                                class="like-btn" data-id="<?php echo $commentaire['id_commentaire']; ?>" data-action="dislike"
                                title="Je n'aime pas"
                                style="color: #1f4f65; font-size: 20px; display: flex; align-items: center; gap: 4px;">
                                <i class="fas fa-thumbs-down"></i>
                                <span style="font-size: 16px;"><?php echo $commentaire['nb_dislikes']; ?></span>
                            </a>
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
                        <div class="comment-actions">
                            <!-- Edit Button -->
                            <button type="button" class="edit-btn" data-id="<?php echo $commentaire['id_commentaire']; ?>"
                                data-content="<?php echo htmlspecialchars($commentaire['contenu_commentaire']); ?>"
                                title="Modifier le commentaire" aria-label="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Delete Button -->
                            <a class="delete-btn open-delete-comment-modal"
                                data-id="<?php echo $commentaire['id_commentaire']; ?>" title="Supprimer le commentaire"
                                aria-label="Supprimer">
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
                <button class="btn primary" style="border-radius: 9px;" type="submit">Commenter</button>
            </form>
        <?php elseif ($isLoggedIn && !$userExists): ?>
            <p style="color: red;">Vous devez être connecté pour laisser un commentaire.</p>
        <?php else: ?>
            <p style="color: red;">Vous devez être connecté pour laisser un commentaire.</p>
        <?php endif; ?>
    </div>

    <?php include '../../assets/footer.php'; ?>

    <!-- Modal for editing comment -->
    <div id="editCommentModal" class="modal" style="display:none;">
        <div class="modal-content"
            style="max-width: 500px; margin: 60px auto; background: #fff; border-radius: 8px; padding: 24px; position: relative;">
            <span class="close-modal" id="closeEditModal"
                style="position: absolute; top: 12px; right: 18px; font-size: 24px; cursor: pointer;">&times;</span>
            <h2>Modifier le commentaire</h2>
            <form id="editCommentForm" method="POST" action="traiter_modif_commentaire.php">
                <input type="hidden" name="id_commentaire" id="edit_id_commentaire">
                <input type="hidden" name="id_article" id="edit_id_article" value="<?php echo $id_article; ?>">
                <div class="form-group">
                    <label for="edit_contenu_commentaire">Votre commentaire</label>
                    <textarea name="contenu_commentaire" id="edit_contenu_commentaire" rows="5" required
                        style="width:100%;"></textarea>
                </div>
                <div class="form-actions" style="margin-top: 16px; text-align: right;">
                    <button type="button" class="btn secondary" id="cancelEditBtn">Annuler</button>
                    <button type="submit" class="btn primary"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal for Comments -->
    <div class="modal" id="deleteCommentModal">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Confirmer la suppression</h2>
                <button class="close-modal" id="closeDeleteCommentModal"
                    style="background: none; border: none; font-size: 24px; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.</p>
                <div class="form-actions" style="margin-top: 16px; text-align: right;">
                    <button type="button" class="btn secondary cancel-btn" id="cancelDeleteCommentBtn">Annuler</button>
                    <button type="button" class="btn danger" id="confirmDeleteCommentBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden Delete Form -->
    <form method="POST" action="supprimer_commentaire.php" style="display:none;" id="deleteCommentForm">
        <input type="hidden" name="id_commentaire" id="delete_comment_id">
        <input type="hidden" name="id_article" value="<?php echo $id_article; ?>">
    </form>
    <script>
        document.querySelectorAll('.open-delete-comment-modal').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('delete_comment_id').value = btn.dataset.id;
                document.getElementById('deleteCommentModal').style.display = 'block';
            });
        });

        document.getElementById('closeDeleteCommentModal').onclick = closeDeleteCommentModal;
        document.getElementById('cancelDeleteCommentBtn').onclick = closeDeleteCommentModal;

        function closeDeleteCommentModal() {
            document.getElementById('deleteCommentModal').style.display = 'none';
        }

        // Confirm delete
        document.getElementById('confirmDeleteCommentBtn').onclick = function () {
            document.getElementById('deleteCommentForm').submit();
        };

        // Optional: close modal when clicking outside
        window.addEventListener('click', function (event) {
            const modal = document.getElementById('deleteCommentModal');
            if (event.target === modal) {
                closeDeleteCommentModal();
            }
        });
    </script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                // Fill modal fields
                document.getElementById('edit_id_commentaire').value = btn.dataset.id;
                document.getElementById('edit_contenu_commentaire').value = btn.dataset.content;
                document.getElementById('editCommentModal').style.display = 'block';
            });
        });

        // Close modal on X or Annuler
        document.getElementById('closeEditModal').onclick = closeEditModal;
        document.getElementById('cancelEditBtn').onclick = closeEditModal;
        function closeEditModal() {
            document.getElementById('editCommentModal').style.display = 'none';
        }

        // Optional: close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('editCommentModal');
            if (event.target === modal) modal.style.display = "none";
        };
    </script>
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
    <script>
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = btn.dataset.id;
                const action = btn.dataset.action;

                fetch(`like_dislike.php?id=${id}&action=${action}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Find the correct comment block and update counts
                            if (action === 'like') {
                                btn.querySelector('.like-count').innerText = data.nb_likes;
                            } else {
                                btn.querySelector('.dislike-count').innerText = data.nb_dislikes;
                            }

                        }
                    });
                window.location.reload();
            });
        });
    </script>
    <script src="../assets/js/profile.js"></script>

</body>

</html>