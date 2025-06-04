<div class="user-profile-dropdown" id="userProfileDropdown">
    <div class="profile-toggle" id="profileToggle">
        <img src="../../../../Controller/get_image.php?file=<?= urlencode(($currentUser ? $currentUser->getImage() : 'default.png')) ?>"
            alt="Profile" class="profile-pic">
        <span><?= $currentUser ? htmlspecialchars($currentUser->getPrenom()) : 'User' ?></span>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-content" id="profileDropdown">
        <a href="../../user/view_profile.php"><i class="fas fa-user"></i> Mon Profil</a>
        <a href="../../user/edit_profile.php"><i class="fas fa-edit"></i> Modifier Profil</a>
        <a href="../../assets/php/calendar.php"> <i class="fas fa-calendar-alt"></i>Calendrier</a>
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
            <a href="../../../BackOffice/index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
        <?php endif; ?>
        <a href="../../../../index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</div>