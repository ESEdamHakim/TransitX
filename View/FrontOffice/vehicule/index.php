<?php
require_once __DIR__ . '/../../../Controller/userC.php';

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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Véhicule</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">

</head>

<body>
    <?php include '../../assets/chatbot/chatbot.php'; ?>
    <?php require_once __DIR__ . '/../../../appConfig.php'; ?>
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
                    <li><a href="../reclamation/index.php">Réclamation</a></li>
                    <li class="active"><a href="index.php">Véhicule</a></li>
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
        <section class="covoiturage-hero">
            <div class="hero-content">
                <h1>Véhicule</h1>
                <p>Partagez vos trajets, économisez de l'argent et réduisez votre empreinte carbone.</p>
                <div style="text-align: center; display: flex; justify-content: center; gap: 1rem;">
                    <a href="#create-ride" class="btn btn-primary">Ajouter votre voiture</a>
                </div>
            </div>
        </section>
        <!--affichage-->
        <section class="popular-routes">
            <div class="container">
                <div class="section-header">
                    <span class="badge">Voitures</span>
                    <h2>Vos voitures</h2>
                </div>
                <div class="route-cards">
                    <?php include 'displayVehicule.php'; ?>
                </div>
            </div>
        </section>
        <!--ajout-->
        <section id="create-ride" class="create-ride-section">
            <div class="container">
                <div class="section-header">
                    <h2>Ajouter une voiture</h2>
                </div>
                <form action="addVehicule.php" method="POST" enctype="multipart/form-data" class="create-ride-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="type-vehicule">Type de Véhicule</label>
                            <select id="type-vehicule" name="type_vehicule" class="form-control">
                                <option value="">-- Sélectionnez un type --</option>
                                <option value="Voiture">Voiture</option>
                                <option value="Moto">Moto</option>
                                <option value="Camion">Camion</option>
                                <option value="Vélo">Vélo</option>
                            </select>
                            <span id="type-vehicule-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="matricule">Matricule</label>
                            <input type="text" id="matricule" name="matricule" placeholder="Numéro de matricule">
                            <span id="matricule-error" class="error-message"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nb-places">Nombre de Places</label>
                            <input type="number" id="nb-places" name="nb_places"
                                placeholder="Nombre de places disponibles">
                            <span id="nb-places-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="couleur">Couleur</label>
                            <input type="text" id="couleur" name="couleur" placeholder="Couleur du véhicule">
                            <span id="couleur-error" class="error-message"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="marque">Marque</label>
                            <input type="text" id="marque" name="marque" placeholder="Marque du véhicule">
                            <span id="marque-error" class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label for="modele">Modèle</label>
                            <input type="text" id="modele" name="modele" placeholder="Modèle du véhicule">
                            <span id="modele-error" class="error-message"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confort">Confort</label>
                        <select id="confort" name="confort" class="form-control">
                            <option value="">-- Sélectionnez une option --</option>
                            <option value="Vitres teintées (fumées)">Vitres teintées (fumées).</option>
                            <option value="Toit ouvrant / panoramique">Toit ouvrant / panoramique.</option>
                            <option value="Sièges chauffants">Sièges chauffants.</option>
                            <option value="Chargeurs USB intégrés">Chargeurs USB intégrés.</option>
                            <option value="Climatisation">Climatisation.</option>
                            <option value="other">Autre...</option>
                        </select>
                        <textarea id="custom-confort" name="custom_confort"
                            placeholder=" complétez l'option sélectionnée" class="form-control"
                            style="margin-top: 10px;"></textarea>
                        <span id="confort-error" class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="photo-vehicule">Ajouter une Photo</label>
                        <input type="file" id="photo-vehicule" name="photo_vehicule" accept="image/*">
                        <span class="error-message" id="photo-vehicule-error"></span>
                    </div>
                    <input type="hidden" name="confort_final" id="confort-final">
                    <button type="submit" class="btn btn-primary">
                        Ajouter le Véhicule
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </section>
    </main>

    <?php include '../../assets/footer.php'; ?>

    <script src="menuToggle.js"></script>
    <script src="validAddVehicule.js"></script>
    <script src="validDeleteVehicule.js"></script>
    <script src="validEditVehicule.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/chatbot.js"> </script>
    <script src="../assets/js/profile.js"></script>
</body>


</html>