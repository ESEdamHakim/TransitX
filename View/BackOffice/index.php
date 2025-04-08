<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TransitX | Tableau de Bord Admin</title>

  <!-- Styles -->
  <link rel="stylesheet" href="assets/dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <!-- Wrap logo and text with a link to front-office index.php -->
        <a href="../FrontOffice/index.php" style="display: flex; align-items: center;">
  <img src="assets/TransitXLogo.png" alt="Logo TransitX" style="width: 40px; margin-right: 10px;" />
  <h2 style="margin: 0;">TransitX</h2>
</a>

      </div>
      <nav class="sidebar-nav">
        <a href="#" class="active"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
        <a href="#"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="bus.php"><i class="fas fa-bus"></i> Bus</a>
        <a href="colis.php"><i class="fas fa-box"></i> Colis</a>
        <a href="#"><i class="fas fa-car-side"></i> Covoiturage</a>
        <a href="#"><i class="fas fa-blog"></i> Blog</a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="main-content">
      <header>
        <h1>Bienvenue Admin</h1>
        <p>Gérez les modules de TransitX et visualisez les statistiques clés.</p>
      </header>

      <!-- Stat Cards -->
      <section class="stats-cards">
        <div class="stat-card">
          <i class="fas fa-users"></i>
          <div>
            <h3>1,250</h3>
            <p>Utilisateurs</p>
          </div>
        </div>
        <div class="stat-card">
          <i class="fas fa-box"></i>
          <div>
            <h3>530</h3>
            <p>Colis Livrés</p>
          </div>
        </div>
        <div class="stat-card">
          <i class="fas fa-car-side"></i>
          <div>
            <h3>180</h3>
            <p>Covoiturages</p>
          </div>
        </div>
        <div class="stat-card">
          <i class="fas fa-bus"></i>
          <div>
            <h3>75</h3>
            <p>Bus Actifs</p>
          </div>
        </div>
      </section>

      <!-- Animated Charts -->
      <section class="charts">
        <div class="chart-box">
          <h4>Livraisons Mensuelles</h4>
          <canvas id="livraisonsChart"></canvas>
        </div>
        <div class="chart-box">
          <h4>Utilisateurs Actifs</h4>
          <canvas id="utilisateursChart"></canvas>
        </div>
      </section>
    </main>
  </div>

  <!-- Chart Scripts -->
  <script>
    // Livraisons Mensuelles (Bar Chart)
    const ctx1 = document.getElementById('livraisonsChart').getContext('2d');
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
          label: 'Colis Livrés',
          data: [50, 70, 65, 90, 80, 120],
          backgroundColor: '#97c3a2',
          borderRadius: 6
        }]
      },
      options: {
        animation: {
          duration: 1000
        },
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Utilisateurs Actifs (Line Chart)
    const ctx2 = document.getElementById('utilisateursChart').getContext('2d');
    new Chart(ctx2, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
          label: 'Utilisateurs Actifs',
          data: [200, 220, 300, 280, 320, 400],
          borderColor: '#1f4f65',
          backgroundColor: 'rgba(31, 79, 101, 0.2)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        animation: {
          duration: 1200
        },
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>

</body>
</html>
