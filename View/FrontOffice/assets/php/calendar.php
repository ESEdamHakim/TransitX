<?php
require_once __DIR__ . '/../../../../Controller/userC.php';
require_once __DIR__ . '/../../../../Controller/CovoiturageC.php';
require_once __DIR__ . '/../../../../Controller/vehiculeC.php';
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
$vehiculeC = new VehiculeC();

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
// 1. Get all covoiturages for the month
$allCovoiturages = $covoiturageC->getAllCovoituragesForMonth($year, $month);

// 2. Filter for covoiturages where the user is the driver
$myCovoiturages = array_filter($allCovoiturages, function ($cov) use ($id_user) {
  return isset($cov['id_user']) && $cov['id_user'] == $id_user;
});

// 3. Get user bookings (as passenger)
$userAcceptedBookings = $covoiturageC->getUserAcceptedBookings($id_user);
$bookedDays = [];
foreach ($userAcceptedBookings as $booking) {
  $date = date('Y-m-d', strtotime($booking['date_depart']));
  $bookedDays[$date][] = $booking['id_covoiturage'];
}

// 4. Combine: my covoiturages + booked covoiturages (avoid duplicates)
$userCovoiturages = $myCovoiturages;
foreach ($userAcceptedBookings as $booking) {
  // Only add if not already in $userCovoiturages
  $already = false;
  foreach ($userCovoiturages as $cov) {
    if ($cov['id_covoit'] == $booking['id_covoiturage']) {
      $already = true;
      break;
    }
  }
  if (!$already) {
    // You may need to fetch full covoiturage details here
    $fullCov = $covoiturageC->getCovoiturageById($booking['id_covoiturage']);
    if ($fullCov)
      $userCovoiturages[] = $fullCov;
  }
}
$userColis = [];
if ($id_user) {
    $userColis = $covoiturageC->getUserColisForMonth($id_user, $year, $month);
}

// Build a lookup array for quick access by date
$colisDays = [];
foreach ($userColis as $colis) {
    $colisDays[$colis['date_colis']] = $colis['statut'];
}
// Calendar generation function
function generateCalendar($year, $month, $holidays, $userCovoiturages, $bookedDays, $covoiturageC, $vehiculeC, $colisDays, $userColis)
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
   $icons = '';
if (!empty($colisDays[$dayDate])) {
    $statut = $colisDays[$dayDate];
    $color = '#3eb7e0'; // default for "en transit"
    if ($statut === 'en attente') {
        $color = '#0e142f';
    } elseif ($statut === 'livré') {
        $color = '#279e25';
    }
    $icons .= '<i class="fa-solid fa-box colis-icon" style="color:'.$color.'"></i>';
}
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
       // Add colis info to details (for modal only)
$colisList = $covoiturageC->getColisForCovoiturage($covoit['id_covoit']);
if (!empty($colisList)) {
    foreach ($colisList as $colis) {
        $details[] = "<br><span style=\"color:#1b485f;font-weight:bold;\">Colis : Propriétaire: {$colis['prenom']} {$colis['nom']} | Tél: {$colis['telephone']} | Statut: {$colis['statut']}</span>";
    }
}
        // Booking logic: check if this covoiturage is booked by the user for this day
        if (!empty($bookedDays[$dayDate]) && in_array($covoit['id_covoit'], $bookedDays[$dayDate], true)) {
          // Fetch vehicle and driver info for this covoiturage
          $id_vehicule = $covoiturageC->getVehiculeIdByCovoiturageId($covoit['id_covoit']);
          $vehicule = $vehiculeC->getVehiculeById($id_vehicule);
          $driver = $covoiturageC->getDriverByCovoiturageId($covoit['id_covoit']);

          // Build the modal content for this booking
          $modalContent = "
          <div class='booked-covoit-modal'>
          <img id='vehicule-photo' src='../../../assets/uploads/{$vehicule['photo_vehicule']}' alt='Photo du véhicule' class='vehicle-img' />
          <h3>Conducteur: {$driver['prenom']} {$driver['nom']}</h3>
          <p><strong>Départ:</strong> {$covoit['lieu_depart']} à {$covoit['temps_depart']}</p>
          <p><strong>Arrivée:</strong> {$covoit['lieu_arrivee']}</p>
          </div>
    ";

          // Set booking flags and detailsStr for the modal
          if ($timestampDay < $today) {
            $isUserPastAcceptedBooking = true;
            $detailsStr = $modalContent;
          } else {
            $isUserFutureAcceptedBooking = true;
            $detailsStr = $modalContent;
          }

        }
      }
    }

    // 1. Booked covoiturage (past) + holiday
    if ($isUserPastAcceptedBooking && $isHoliday) {
      $class[] = 'votre-covoiturage-past';
      $detailsStr .= "<br><span style=\"color:#7ba987;font-weight:bold;\">Jour férié : $holidayName</span>";
      $onclick = 'onclick="showBookedCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ', \'Votre covoiturage réservé\')"';
    }
    // 2. Booked covoiturage (future) + holiday
    elseif ($isUserFutureAcceptedBooking && $isHoliday) {
      $class[] = 'votre-covoiturage';
      $detailsStr .= "<br><span style=\"color:#7ba987;font-weight:bold;\">Jour férié : $holidayName</span>";
      $onclick = 'onclick="showBookedCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ', \'Votre covoiturage réservé\')"';
    }
    // 3. Booked covoiturage (past) only
    elseif ($isUserPastAcceptedBooking && !$isHoliday) {
      $class[] = 'votre-covoiturage-past';
      $onclick = 'onclick="showBookedCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ', \'Votre covoiturage réservé\')"';
    }
    // 4. Booked covoiturage (future) only
    elseif ($isUserFutureAcceptedBooking && !$isHoliday) {
      $class[] = 'votre-covoiturage';
      $onclick = 'onclick="showBookedCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ', \'Votre covoiturage réservé\')"';
    }
    // 5. Only holiday
    elseif ($isHoliday && !$isCovoit) {
      $class[] = 'holiday';
      $onclick = "onclick='showHolidayDetails(" . json_encode($holidayName) . ")'";
    }
    // 6. Only future covoiturage(s)
    elseif (!$isHoliday && $hasFutureCovoit) {
      $class[] = 'covoiturage-future';
      $detailsStr = implode('<br>', $details);
      $onclick = "onclick='showCovoiturageDetails(" . json_encode($detailsStr) . ")'";
    }
    // 7. Only past or full covoiturage(s)
    elseif (!$isHoliday && !$hasFutureCovoit && $hasPastOrFullCovoit) {
      $class[] = 'covoiturage-past';
      $detailsStr = implode('<br>', $details);
     $onclick = 'onclick="showCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ')"';
    }
    // 8. Both holiday and covoiturage (not booked)
    elseif ($isHoliday && $isCovoit) {
      $class[] = 'holiday';
      $detailsStr = "<span style=\"color:#7ba987;font-weight:bold;\">$holidayName</span><br>" . implode('<br>', $details);
      $onclick = 'onclick="showCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ')"';
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
     $onclick = 'onclick="showCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ')"';
    }
    // 4. Only past or full covoiturage(s)
    elseif (!$isHoliday && !$hasFutureCovoit && $hasPastOrFullCovoit) {
      $class[] = 'covoiturage-past';
      $detailsStr = implode('<br>', $details);
      $onclick = 'onclick="showCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ')"';
    }
    // 5. Both holiday and covoiturage (any kind)
    elseif ($isHoliday && $isCovoit) {
      $class[] = 'holiday';
      $detailsStr = "<span style=\"color:#7ba987;font-weight:bold;\">$holidayName</span><br>" . implode('<br>', $details);
      $onclick = 'onclick="showHolidayCovoiturageDetails(' . htmlspecialchars(json_encode($detailsStr), ENT_QUOTES) . ')"';
    }

    $classAttr = $class ? " class='" . implode(' ', $class) . "'" : '';
    //$calendar .= "<td$classAttr " . htmlspecialchars($onclick, ENT_QUOTES) . ">$day</td>";
    $calendar .= "<td$classAttr $onclick>$icons$day</td>";

    if ($weekday == 6 && $day !== $daysInMonth) {
      $calendar .= "</tr><tr>";
    }
  }
//echo '<pre>'; print_r($userColis); echo '</pre>';
//echo '<pre>'; print_r($colisDays); echo '</pre>';
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
    echo generateCalendar($year, $month, $holidays, $userCovoiturages, $bookedDays, $covoiturageC, $vehiculeC, $colisDays, $userColis)

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