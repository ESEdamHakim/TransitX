<!DOCTYPE html>
<html lang="fr">

</html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Véhicule</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
        <?php if (isset($user_type) && $user_type !== 'client'): ?>
          <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <?php endif; ?>
                <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    <main>
        <section class="covoiturage-hero">
            <div class="hero-content">
                <h1>Véhicule</h1>
                <p>Partagez vos trajets, économisez de l'argent et réduisez votre empreinte carbone.</p>
                <div class="hero-buttons">
                    <a href="#create-ride" class="btn btn-outline">Ajouter votre voiture</a>
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
                            <option value="Chargeurs USB intégrés.">Chargeurs USB intégrés.</option>
                            <option value="Climatisation">Climatisation.</option>
                            <option value="other">Autre...</option>
                        </select>
                        <textarea id="custom-confort" name="custom_confort"
                            placeholder=" complétez l'option sélectionnée" class="form-control"
                            style="margin-top: 10px;"></textarea>
                        <span id="confort-error" class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="photo-vehicule">Ajouter une Photo :</label>
                        <input type="file" id="photo-vehicule" name="photo_vehicule" accept="image/*">
                        <span class="error-message" id="photo-vehicule-error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Ajouter le Véhicule
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <script src="validAddVehicule.js"></script>
                <script src="validDeleteVehicule.js"></script>
                <script src="validEditVehicule.js"></script>
                <script src="menuToggle.js"></script>
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
                        <li><a href="index.php">Covoiturage</a></li>
                        <li><a href="../colis/index.php">Colis</a></li>
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
        <script src="validAddVehicule.js"></script>
        <script src="validDeleteVehicule.js"></script>
        <script src="validEditVehicule.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="assets/js/chatbot.js"> </script>

    </footer>
</body>


</html>