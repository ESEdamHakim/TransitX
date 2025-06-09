<div class="user-profile-dropdown" id="userProfileDropdown">
    <div class="profile-toggle" id="profileToggle">
        <img src="../../Controller/get_image.php?file=<?= urlencode(($currentUser ? $currentUser->getImage() : 'default.png')) ?>"
            alt="Profile" class="profile-pic">
        <span><?= $currentUser ? htmlspecialchars($currentUser->getPrenom()) : 'User' ?></span>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-content" id="profileDropdown">
        <a href="assets/php/calendar.php"> <i class="fas fa-calendar-alt"></i>Calendrier</a>
        <a href="../../index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</div>