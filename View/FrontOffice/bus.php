<?php
// Example of bus data (replace with your dynamic database query)
$buses = [
    [
        'image' => 'busimage.png',
        'numero' => 'TX-101',
        'capacite' => 50,
        'type' => 'Standard',
        'trajet' => 'Tunis - Sousse',
        'duree' => '2h 30min',
        'frequence' => 'Quotidienne',
    ],
    [
        'image' => 'busimage.png',
        'numero' => 'TX-202',
        'capacite' => 40,
        'type' => 'Mini',
        'trajet' => 'Ariana - La Marsa',
        'duree' => '45min',
        'frequence' => 'Toutes les 2 heures',
    ],
    [
        'image' => 'busimage.png',
        'numero' => 'TX-303',
        'capacite' => 60,
        'type' => 'Double decker',
        'trajet' => 'Tunis - Sfax',
        'duree' => '3h',
        'frequence' => 'Hebdomadaire',
    ],
    [
        'image' => 'busimage.png',
        'numero' => 'TX-404',
        'capacite' => 55,
        'type' => 'Luxury',
        'trajet' => 'Sousse - Tunis',
        'duree' => '2h 15min',
        'frequence' => 'Quotidienne',
    ],
    [
        'image' => 'busimage.png',
        'numero' => 'TX-505',
        'capacite' => 35,
        'type' => 'Mini',
        'trajet' => 'Tunis - Bizerte',
        'duree' => '1h 30min',
        'frequence' => 'Toutes les 3 heures',
    ],
    [
        'image' => 'busimage.png',
        'numero' => 'TX-606',
        'capacite' => 65,
        'type' => 'Double decker',
        'trajet' => 'Sfax - Djerba',
        'duree' => '4h 30min',
        'frequence' => 'Hebdomadaire',
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>TransitX | Galerie des Bus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Découvrez notre collection de bus." />
    <meta name="author" content="TransitX Team" />

    <!-- Bootstrap & Custom CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/styles.css" />

    <style>
   /* Gallery and Hover Effect Styles */
.bus-card {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin-bottom: 30px;
}

.bus-image {
    width: 100%;
    height: 300px;
    background-size: cover;
    background-position: center;
    transition: opacity 0.3s ease;
}

.bus-info {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #fff;  /* Default text color */
    opacity: 0;
    transition: opacity 0.3s ease, color 0.3s ease; /* Add transition for color change */
}

.bus-card:hover .bus-image {
    opacity: 0;
}

.bus-card:hover .bus-info {
    opacity: 1;
    color: #000;  /* Change text color to black on hover */
}

.bus-info h4, .bus-info p {
    margin-bottom: 10px;
}

/* Ensuring the background is consistent with backoffice */
body {
    background-color: #ffffff;
    font-family: 'Segoe UI', sans-serif;
    color: #1f4f65;
}

header {
    background-color: #97c3a2;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    gap: 230px;
}

.logo-text {
    font-size: 24px;
    font-weight: bold;
    color: #1f4f65;
    margin-left: 10px;
}

nav a {
    font-size: 18px;
    font-weight: 500;
    color: #fff;
    margin-left: 30px;
    transition: 0.3s;
}

nav a.active, nav a:hover {
    color: #f9d86d;
}

h3 {
    color: #1f4f65;
    margin-bottom: 15px;
}

footer {
    background-color: #1f4f65;
    color: white;
    padding: 20px;
    text-align: center;
}

footer p {
    margin: 0;
}

.bus-gallery h3 {
    color: #1f4f65;
    font-size: 36px;
}

/* Button styles */
.btn-success {
    background-color: #97c3a2;
    border: none;
    color: white;
    font-weight: bold;
}

.btn-success:hover {
    background-color: #1f4f65;
}

.btn-palette-green {
    background-color: #97c3a2;
    border: none;
    color: white;
    font-weight: bold;
    padding: 5px 12px;
    border-radius: 4px;
}

.btn-palette-green:hover {
    background-color: #7ca88a;
}

.btn-palette-dark {
    background-color: #1f4f65;
    border: none;
    color: white;
    font-weight: bold;
    padding: 5px 12px;
    border-radius: 4px;
}

.btn-palette-dark:hover {
    background-color: #173b4c;
}

</style>

</head>

<body>
    <!-- Navigation Header -->
    <header class="header">
        <a class="logo d-flex align-items-center" href="index.php">
            <img src="assets/TransitXLogo.png" alt="TransitX Logo" height="50" />
            <span class="logo-text">TransitX</span>
        </a>
        <nav class="nav">
            <a href="index.php">Accueil</a>
            <a href="bus.php" class="active">Buses</a>
            <a href="colis.php">Colis</a>
            <a href="#">Covoiturage</a>
            <a href="#">Blog</a>
            <a href="#">À propos</a>
            <a href="#">Contact</a>
        </nav>
    </header>

    <!-- Bus Gallery Section -->
    <section class="bus py-5">
        <div class="container">
            <h3 class="text-center mb-4">Nos Bus</h3>
            <div class="row">
                <?php foreach ($buses as $bus): ?>
                    <div class="col-md-4">
                        <div class="bus-card">
                            <!-- Dynamically setting the background image for each bus -->
                            <div class="bus-image" style="background-image: url('assets/img/<?php echo $bus['image']; ?>');"></div>
                            <div class="bus-info">
                                <h4>Numéro: <?php echo $bus['numero']; ?></h4>
                                <p>Capacité: <?php echo $bus['capacite']; ?> passagers</p>
                                <p>Type: <?php echo $bus['type']; ?></p>
                                <p>Trajet: <?php echo $bus['trajet']; ?></p>
                                <p>Durée: <?php echo $bus['duree']; ?></p>
                                <p>Fréquence: <?php echo $bus['frequence']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white text-center">
        <p>&copy; 2025 TransitX | Tous droits réservés</p>
    </footer>

    <!-- Optional: Bootstrap JS (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
