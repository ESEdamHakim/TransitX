<?php
$apiKey = 'FitXplCVELZM84MwY8fo9JwsUejXs9fO'; // Ta clé API
$countryCode = 'TN'; // Code pays, ici pour la Tunisie (TN)
$year = date("Y"); // Année en cours
$month = isset($_GET['month']) ? $_GET['month'] : date("m"); // Mois actuel ou passé via GET

// Calculer l'année et le mois précédent et suivant
$prevMonth = ($month == 1) ? 12 : $month - 1;
$nextMonth = ($month == 12) ? 1 : $month + 1;
$prevYear = ($month == 1) ? $year - 1 : $year;
$nextYear = ($month == 12) ? $year + 1 : $year;

// URL de l'API pour récupérer les jours fériés en Tunisie
$url = "https://calendarific.com/api/v2/holidays?api_key=$apiKey&country=$countryCode&year=$year&month=$month";

// Récupérer les données de l'API
$response = @file_get_contents($url); // @ pour éviter les erreurs d'affichage

if (!$response) {
  echo "<p>Erreur de récupération des données de l'API.</p>";
  exit;
}

$data = json_decode($response, true);

// Récupérer les jours fériés dans un tableau pour un accès rapide
$holidays = [];
if (isset($data['response']['holidays'])) {
  foreach ($data['response']['holidays'] as $holiday) {
    $holidayDate = date('j', strtotime($holiday['date']['iso'])); // Extraire le jour du mois
    $holidays[$holidayDate] = $holiday['name']; // Ajouter le nom du jour férié par date
  }
}

// Fonction pour créer un calendrier
function generateCalendar($year, $month, $holidays)
{
  $firstDay = strtotime("$year-$month-01");
  $lastDay = strtotime("$year-$month-" . date('t', $firstDay)); // Dernier jour du mois
  $daysInMonth = date('t', $firstDay); // Nombre de jours dans le mois

  $calendar = "<table class='calendar'>";
  $calendar .= "<thead><tr><th>Dim</th><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th></tr></thead>";
  $calendar .= "<tbody><tr>";

  // Ajout des espaces vides avant le premier jour du mois
  for ($i = 0; $i < date('w', $firstDay); $i++) {
    $calendar .= "<td></td>";
  }

  // Remplissage des jours du mois
  for ($day = 1; $day <= $daysInMonth; $day++) {
    if (isset($holidays[$day])) {
      $holidayName = $holidays[$day];
      $calendar .= "<td class='holiday' data-holiday='$holidayName' onclick='showHolidayDetails(\"$holidayName\")'>$day</td>"; // Affichage du nom du jour férié dans l'info-bulle
    } else {
      $calendar .= "<td>$day</td>";
    }

    // Saut de ligne à la fin de chaque semaine
    if (date('w', strtotime("$year-$month-$day")) == 6) {
      $calendar .= "</tr><tr>";
    }
  }

  // Compléter la dernière ligne du tableau si nécessaire
  while (date('w', strtotime("$year-$month-$daysInMonth")) != 6) {
    $calendar .= "<td></td>";
    $daysInMonth++;
  }

  $calendar .= "</tr></tbody></table>";

  return $calendar;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Blog</title>

  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

  <style>
    .calendar {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }

    .calendar th,
    .calendar td {
      padding: 10px;
      width: 14%;
      height: 60px;
      font-size: 16px;
    }

    .calendar th {
      background-color: #f0f0f0;
    }

    .calendar td {
      background-color: #fff;
      border: 1px solid #ddd;
    }

    .calendar td.holiday {
      background-color: #ffcccb;
      border: 2px solid #f00;
      font-weight: bold;
      cursor: pointer;
    }

    .calendar td:hover {
      background-color: #f1f1f1;
    }

    .calendar-navigation {
      text-align: center;
      margin: 20px 0;
    }

    .calendar-navigation a {
      font-size: 18px;
      text-decoration: none;
      margin: 0 10px;
    }

    /* Styles pour la modale */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .calendar-navigation {
      text-align: center;
      margin: 20px 0;
      font-size: 24px;
    }

    .calendar-navigation a {
      color: #333;
      text-decoration: none;
      padding: 10px;
    }

    .calendar-navigation a:hover {
      color: #007bff;
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

  <section class="blog" id="blog">
    <!-- Calendrier avec les jours fériés -->
    <section id="calendrier">
      <h2 style="text-align: center;">Calendrier du mois <?php echo $month; ?> - <?php echo $year; ?></h2>
      <?php
      // Afficher le calendrier du mois avec les jours fériés
      echo generateCalendar($year, $month, $holidays);
      ?>
    </section>

    <div class="calendar-navigation">
      <a href="?year=<?php echo $prevYear; ?>&month=<?php echo $prevMonth; ?>" title="Mois précédent">
        <i class="fas fa-chevron-left"></i>
      </a>
      <span style="margin: 0 15px; font-weight: bold;">
        <?php echo date("F", mktime(0, 0, 0, $month, 1)) . " " . $year; ?>
      </span>
      <a href="?year=<?php echo $nextYear; ?>&month=<?php echo $nextMonth; ?>" title="Mois suivant">
        <i class="fas fa-chevron-right"></i>
      </a>
    </div>

    <div class="blog-posts">
      <!-- Articles dynamiques ajoutés ici par JS -->
    </div>
  </section>

  <!-- Modale pour afficher les détails du jour férié -->
  <div id="holidayModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="holidayTitle"></h2>
      <p id="holidayDescription"></p>
    </div>
  </div>

  <?php include '../../assets/footer.php'; ?>

  <script>
    function showHolidayDetails(holidayName) {
      // Afficher la modale avec le nom du jour férié
      document.getElementById("holidayTitle").innerText = holidayName;
      document.getElementById("holidayModal").style.display = "block";
    }

    function closeModal() {
      document.getElementById("holidayModal").style.display = "none";
    }
  </script>
</body>

</html>