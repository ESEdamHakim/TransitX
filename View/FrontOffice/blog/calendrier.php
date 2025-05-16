<?php
$apiKey = 'FitXplCVELZM84MwY8fo9JwsUejXs9fO';
$countryCode = 'TN';
$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT) ?: date("Y");
$month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT) ?: date("n"); 

$month = max(1, min(12, (int) $month));

// Previous and next month/year logic
$prevDate = strtotime("-1 month", strtotime("$year-$month-01"));
$nextDate = strtotime("+1 month", strtotime("$year-$month-01"));
$prevMonth = (int)date("n", $prevDate);
$nextMonth = (int)date("n", $nextDate);
$prevYear = date("Y", $prevDate);
$nextYear = date("Y", $nextDate);

// API call
$url = "https://calendarific.com/api/v2/holidays?api_key=$apiKey&country=$countryCode&year=$year&month=$month";

// Add caching here
$cacheFile = __DIR__ . "/assets/cache/cache_{$year}_{$month}.json";
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) { // 1 day cache
    $response = file_get_contents($cacheFile);
} else {
    $response = @file_get_contents($url);
    if ($response) file_put_contents($cacheFile, $response);
}

if (!$response) {
  echo "<p>Erreur de récupération des données de l'API.</p>";
  exit;
}

$data = json_decode($response, true);

if (!$response) {
  echo "<p>Erreur de récupération des données de l'API.</p>";
  exit;
}

$data = json_decode($response, true);

// Extract holidays
$holidays = [];
foreach ($data['response']['holidays'] ?? [] as $holiday) {
  $day = date('j', strtotime($holiday['date']['iso']));
  $holidays[$day] = $holiday['name'];
}

// Calendar generation function
function generateCalendar($year, $month, $holidays)
{
  $firstDay = strtotime("$year-$month-01");
  $daysInMonth = date('t', $firstDay);
  $calendar = "<table class='calendar'><thead><tr>";
  $days = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

  foreach ($days as $dayName) {
    $calendar .= "<th>$dayName</th>";
  }
  $calendar .= "</tr></thead><tbody><tr>";

  // Fill empty cells before the 1st
  for ($i = 0; $i < date('w', $firstDay); $i++) {
    $calendar .= "<td></td>";
  }

  // Fill days
  for ($day = 1; $day <= $daysInMonth; $day++) {
    $weekday = date('w', strtotime("$year-$month-$day"));
    $isHoliday = isset($holidays[$day]);
    $holidayName = $holidays[$day] ?? '';
    $class = $isHoliday ? " class='holiday' onclick='showHolidayDetails(\"$holidayName\")'" : '';
    $calendar .= "<td$class>$day</td>";

    if ($weekday == 6 && $day !== $daysInMonth) {
      $calendar .= "</tr><tr>";
    }
  }

  // Complete final row
  $lastWeekday = date('w', strtotime("$year-$month-$daysInMonth"));
  for ($i = $lastWeekday + 1; $i <= 6; $i++) {
    $calendar .= "<td></td>";
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
        <a href="../../BackOffice/index.php" class="btn secondary">Dashboard</a>
        <a href="../../../index.php" class="btn primary">Déconnexion</a>
        <button class="mobile-menu-btn">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <section class="blog" id="blog">
    <!-- Calendrier avec les jours fériés -->
     <br>
    <section id="calendrier">
      <h2 style="text-align: center;">Calendrier du mois <?php echo $month; ?> - <?php echo $year; ?></h2>
      <br>
      <?php
      // Afficher le calendrier du mois avec les jours fériés
      echo generateCalendar($year, $month, $holidays);
      ?>
    </section>

    <div class="calendar-navigation">
      <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" title="Mois précédent">
        <i class="fas fa-chevron-left"></i>
      </a>
      <span style="margin: 0 15px; font-weight: bold;">
        <?php echo date("F", mktime(0, 0, 0, $month, 1)) . " $year"; ?>
      </span>
      <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" title="Mois suivant">
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
    function showHolidayDetails(name) {
      document.getElementById("holidayTitle").innerText = name;
      document.getElementById("holidayModal").style.display = "block";
    }

    function closeModal() {
      document.getElementById("holidayModal").style.display = "none";
    }

    // Optional: Close modal when clicking outside
    window.onclick = function (e) {
      if (e.target == document.getElementById("holidayModal")) {
        closeModal();
      }
    }
  </script>
</body>

</html>