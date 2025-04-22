<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Véhicule</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
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
                    <li><a href="../blog/index.php">Blog</a></li>
                    <li><a href="../reclamation/index.php">Réclamation</a></li>
                    <li class="active"><a href="index.php">Véhicule</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
                <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <main>
        <section class="vehicule-hero">
            <div class="hero-content">
                <h1>Gestion des Véhicules</h1>
                <p>Ajoutez, modifiez ou supprimez vos véhicules pour les trajets de covoiturage.</p>
                <div class="hero-buttons">
                    <a href="#add-vehicule" class="btn btn-primary">Ajouter un véhicule</a>
                    <a href="#list-vehicules" class="btn btn-outline">Voir mes véhicules</a>
                </div>
            </div>
        </section>
        <!-- Add more sections for vehicle management -->
        <section class="user-vehicles">
            <div class="container">
                <div class="section-header">
                    <span class="badge">Vos Véhicules</span>
                    <h2>Vos Véhicules</h2>
                    <p>Voici les véhicules que vous avez ajoutés.</p>
                </div>
                <div class="vehicle-cards">
                </div>
            </div>
        </section>

        <section id="add-vehicle" class="add-vehicle-section">
    <div class="container">
        <div class="section-header">
            <h2>Ajouter un Véhicule</h2>
        </div>
        <form class="add-vehicle-form" method="POST" enctype="multipart/form-data"> <!--novalidate-->
            <div class="form-row">
                <div class="form-group">
                    <label for="matricule">Matricule</label>
                    <input type="text" id="matricule" name="matricule" placeholder="Numéro de matricule">
                    <span id="matricule-error" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="type-vehicule">Type de Véhicule</label>
                    <input type="text" id="type-vehicule" name="type_vehicule" placeholder="Type de véhicule (ex: Voiture)">
                    <span id="type-vehicule-error" class="error-message"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nb-places">Nombre de Places</label>
                    <input type="number" id="nb-places" name="nb_places" placeholder="Nombre de places disponibles">
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
            <div class="form-row">
                <div class="form-group">
                    <label for="confort">Confort</label>
                    <select id="confort" name="confort">
                        <option value="">-- Sélectionnez une option --</option>
                        <option value="Basique">Basique</option>
                        <option value="Confortable">Confortable</option>
                        <option value="Luxe">Luxe</option>
                    </select>
                    <span id="confort-error" class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="photo-vehicule">Photo du Véhicule</label>
                    <input type="file" id="photo-vehicule" name="photo_vehicule">
                    <span id="photo-vehicule-error" class="error-message"></span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                Ajouter le Véhicule
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</section>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 TransitX. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>