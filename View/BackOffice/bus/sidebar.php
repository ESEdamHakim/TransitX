<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="logo">
      <img src="../../assets/images/logo.png" alt="TransitX Logo" class="nav-logo">
      <span>Transit</span><span class="highlight">X</span>
    </div>
    <button class="sidebar-toggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <nav class="sidebar-menu">
    <ul>
      <li><a href="../index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
      <li><a href="../users/crud.php"><i class="fas fa-users"></i><span>Utilisateurs</span></a></li>
      <li class="active"><a href="crud.php"><i class="fas fa-bus"></i><span>Bus</span></a></li>
      <li><a href="../trajets/crud.php"><i class="fas fa-road"></i><span>Trajets</span></a></li>
      <li><a href="../colis/crud.php"><i class="fas fa-box"></i><span>Colis</span></a></li>
      <li><a href="../reclamations/crud.php"><i class="fas fa-exclamation-circle"></i><span>Réclamations</span></a></li>
      <li><a href="../covoiturage/crud.php"><i class="fas fa-car-side"></i><span>Covoiturage</span></a></li>
      <li><a href="../blog/crud.php"><i class="fas fa-blog"></i><span>Blog</span></a></li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <a href="#" class="user-profile">
      <img src="../assets/images/placeholder-admin.png" alt="Admin" class="user-img">
      <div class="user-info">
        <h4>Admin User</h4>
        <p>Administrateur</p>
      </div>
    </a>
    <a href="../../../index.php" class="logout">
      <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
    </a>
  </div>
</aside>
<script>
    document.querySelector('.sidebar-toggle').addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
  });
</script>
