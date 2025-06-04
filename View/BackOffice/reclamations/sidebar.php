<div class="dashboard">
     <aside class="sidebar">
      <div class="sidebar-header">
        <a href="../../FrontOffice/index.php" class="logoback-link">
          <div class="logoback">
            <img src="../../assets/images/logo.png" alt="TransitX Logoback" class="nav-logo">
            <span class="logo-text">Transit</span><span class="highlight logo-text">X</span>
          </div>
        </a>
        <button class="sidebar-toggle">
          <i class="fas fa-bars"></i>
        </button>
      </div>

      <div class="sidebar-content">
        <nav class="sidebar-menu">
          <ul>
            <li>
              <a href="../index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="../users/crud.php">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
              </a>
            </li>
            <li>
              <a href="../bus/crud.php">
                <i class="fas fa-bus"></i>
                <span>Bus</span>
              </a>
            </li>
            <li><a href="../trajets/crud.php"><i class="fas fa-road"></i><span>Trajets</span></a></li>

            <li>
              <a href="../colis/crud.php">
                <i class="fas fa-box"></i>
                <span>Colis</span>
              </a>
            </li>
            <li class="active">
              <a href="crud.php">
                <i class="fas fa-exclamation-circle"></i>
                <span>Réclamations</span>
              </a>
            </li>
            <li>
              <a href="../covoiturage/crud.php">
                <i class="fas fa-car-side"></i>
                <span>Covoiturage</span>
              </a>
            </li>
            <li>
              <a href="../blog/crud.php">
                <i class="fas fa-blog"></i>
                <span>Blog</span>
              </a>
            </li>
            <li><a href="../vehicule/crud.php"><i class="fas fa-car"></i><span>Véhicules</span></a></li>

          </ul>
          <a href="../../../index.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
          </a>
        </nav>
      </div>
    </aside>
</div>

<script>
    document.querySelector('.sidebar-toggle').addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.main-content').classList.toggle('expanded');
    });
</script>