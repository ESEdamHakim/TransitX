<?php
require_once __DIR__ . '/../../../../Controller/userC.php';
require_once __DIR__ . '/../../../../Controller/CovoiturageC.php';
session_start(); // Important : Démarrer la session en haut du fichier

$userController = new UserC();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Set $id_user from session
$id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$userController = new UserC();

// Only get currentUser if you need it
$currentUser = null;
if ($id_user) {
  $currentUser = $userController->showUser($id_user);
}




$apiKey = 'FitXplCVELZM84MwY8fo9JwsUejXs9fO';
$countryCode = 'TN';
$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT) ?: date("Y");
$month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT) ?: date("n");

$month = max(1, min(12, (int) $month));
$covoiturageC = new CovoiturageC();
$userCovoiturages = $covoiturageC->getAllCovoituragesForMonth($year, $month);
// Previous and next month/year logic
$prevDate = strtotime("-1 month", strtotime("$year-$month-01"));
$nextDate = strtotime("+1 month", strtotime("$year-$month-01"));
$prevMonth = (int) date("n", $prevDate);
$nextMonth = (int) date("n", $nextDate);
$prevYear = date("Y", $prevDate);
$nextYear = date("Y", $nextDate);

// API call
$url = "https://calendarific.com/api/v2/holidays?api_key=$apiKey&country=$countryCode&year=$year&month=$month";

// Add caching here
$cacheFile = __DIR__ . "/../cache/cache_{$year}_{$month}.json";
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) { // 1 day cache
  $response = file_get_contents($cacheFile);
} else {
  $response = @file_get_contents($url);
  if ($response)
    file_put_contents($cacheFile, $response);
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

// Get user bookings
$userAcceptedBookings = $covoiturageC->getUserAcceptedBookings($id_user);

$bookedDays = [];
foreach ($userAcceptedBookings as $booking) {
  $date = date('Y-m-d', strtotime($booking['date_depart']));
  $bookedDays[$date][] = $booking['id_covoiturage'];
}


// Calendar generation function
function generateCalendar($year, $month, $holidays, $userCovoiturages, $bookedDays)
{
  // Prepare covoiturage days for quick lookup
  $covoitDays = [];
  foreach ($userCovoiturages as $covoit) {
    $date = strtotime($covoit['date_depart']);
    if (date('Y', $date) == $year && date('n', $date) == $month) {
      $day = (int) date('j', $date);
      $covoitDays[$day][] = $covoit;
    }
  }

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

  $today = strtotime(date('Y-m-d'));

  // Fill days
  for ($day = 1; $day <= $daysInMonth; $day++) {
    $weekday = date('w', strtotime("$year-$month-$day"));
    $isHoliday = isset($holidays[$day]);
    $holidayName = $holidays[$day] ?? '';
    $isCovoit = isset($covoitDays[$day]);


    $class = [];
    $onclick = '';
    $details = [];
    $hasFutureCovoit = false;
    $hasPastOrFullCovoit = false;
    $isUserPastAcceptedBooking = false;
    $isUserFutureAcceptedBooking = false;
    $dayDate = date('Y-m-d', strtotime("$year-$month-$day"));
    $timestampDay = strtotime($dayDate);
    if ($isCovoit) {
      foreach ($covoitDays[$day] as $covoit) {
        $covoitDate = strtotime($covoit['date_depart']);
        $placesDispo = isset($covoit['places_dispo']) ? $covoit['places_dispo'] : 0;
        if ($covoitDate >= $today && $placesDispo > 0) {
          $hasFutureCovoit = true;
        } else {
          $hasPastOrFullCovoit = true;
        }
        $details[] = "De {$covoit['lieu_depart']} à {$covoit['lieu_arrivee']} à {$covoit['temps_depart']}";
        // Booking logic: check if this covoiturage is booked by the user for this day
        if (!empty($bookedDays[$dayDate]) && in_array($covoit['id_covoit'], $bookedDays[$dayDate], true)) {
          if ($timestampDay < $today) {
            $isUserPastAcceptedBooking = true;
          } else {
            $isUserFutureAcceptedBooking = true;
          }
        }
      }
    }

    // 1. "Votre covoiturage" (user has a booking for this day) - takes priority
    if ($isUserFutureAcceptedBooking) {
      $class[] = 'votre-covoiturage';
      $detailsStr = "<span style=\"color:#fff;font-weight:bold;\">Votre covoiturage réservé</span><br>" . implode('<br>', $details);
      $onclick = "onclick='showCovoiturageDetails(" . json_encode($detailsStr) . ", \"Votre covoiturage réservé\")'";
    } elseif ($isUserPastAcceptedBooking) {
      $class[] = 'votre-covoiturage-past';
      $detailsStr = "<span style=\"color:#fff;font-weight:bold;\">Votre covoiturage passé</span><br>" . implode('<br>', $details);
      $onclick = "onclick='showCovoiturageDetails(" . json_encode($detailsStr) . ", \"Votre covoiturage passé\")'";
    }
    // 2. Only holiday
    elseif ($isHoliday && !$isCovoit) {
      $class[] = 'holiday';
      $onclick = "onclick='showHolidayDetails(" . json_encode($holidayName) . ")'";
    }
    // 3. Only future covoiturage(s)
    elseif (!$isHoliday && $hasFutureCovoit) {
      $class[] = 'covoiturage-future';
      $detailsStr = implode('<br>', $details);
      $onclick = "onclick='showCovoiturageDetails(" . json_encode($detailsStr) . ")'";
    }
    // 4. Only past or full covoiturage(s)
    elseif (!$isHoliday && !$hasFutureCovoit && $hasPastOrFullCovoit) {
      $class[] = 'covoiturage-past';
      $detailsStr = implode('<br>', $details);
      $onclick = "onclick='showCovoiturageDetails(" . json_encode($detailsStr) . ")'";
    }
    // 5. Both holiday and covoiturage (any kind)
    elseif ($isHoliday && $isCovoit) {
      $class[] = 'holiday';
      $detailsStr = "<span style=\"color:#7ba987;font-weight:bold;\">$holidayName</span><br>" . implode('<br>', $details);
      $onclick = "onclick='showHolidayCovoiturageDetails(" . json_encode($detailsStr) . ")'";
    }

    $classAttr = $class ? " class='" . implode(' ', $class) . "'" : '';
    $calendar .= "<td$classAttr $onclick>$day</td>";

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

  <link rel="stylesheet" href="../../../assets/css/main.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/calendar.css">
  <link rel="stylesheet" href="../../../assets/css/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

</head>

<body>
  <header class="landing-header">
    <div class="container">
      <div class="header-left">
        <div class="logo">
          <img src="../../../assets/images/logo.png" alt="TransitX Logo" class="main-logo">
          <span class="logo-text">TransitX</span>
        </div>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="../../index.php">Accueil</a></li>
          <li><a href="../../bus/index.php">Bus</a></li>
          <li><a href="../../colis/index.php">Colis</a></li>
          <li><a href="../../covoiturage/index.php">Covoiturage</a></li>
          <li><a href="../../blog/index.php">Blog</a></li>
          <li><a href="../../reclamation/index.php">Réclamation</a></li>
          <li><a href="../../vehicule/index.php">Véhicule</a></li>
        </ul>
      </nav>
      <div class="header-right">
        <div class="actions">
          <div class="actions-container">
            <?php include 'calendarprofile.php'; ?>
          </div>
          <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </header>


  <!-- Calendrier avec les jours fériés -->
  <section id="calendrier">
    <div class="calendar-navigation">
      <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" title="Mois précédent">
        <i class="fas fa-chevron-left"></i>
      </a>
      <span style="margin: 0 15px; font-weight: bold;font-size: 1.8rem;">
        <?php echo date("F", mktime(0, 0, 0, $month, 1)) . " $year"; ?>
      </span>
      <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" title="Mois suivant">
        <i class="fas fa-chevron-right"></i>
      </a>
    </div>
    <?php
    // Afficher le calendrier du mois avec les jours fériés
    echo generateCalendar($year, $month, $holidays, $userCovoiturages, $bookedDays);
    ?>

  </section>


  <!-- Modale pour afficher les détails du jour férié -->
  <div id="holidayModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="holidayTitle"></h2>
      <p id="holidayDescription"></p>
    </div>
  </div>
  <!-- Modale pour afficher les détails du covoiturage -->
  <div id="covoiturageModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeCovoiturageModal()">&times;</span>
      <h2>Détails du covoiturage</h2>
      <p id="covoiturageDescription"></p>
    </div>
  </div>
  <!-- Modale pour afficher les détails du jour férié + covoiturage -->
  <div id="holidayCovoiturageModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeHolidayCovoiturageModal()">&times;</span>
      <h2>jour férié et covoiturage</h2>
      <p id="holidayCovoiturageDescription"></p>
    </div>

  </div>
  <?php include '../../../assets/calendarfooter.php'; ?>

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
  <script src="../js/profile.js"></script>
  <script src="../js/calendrierCovoiturage.js"></script>
</body>

</html>