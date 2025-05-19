document.addEventListener("DOMContentLoaded", () => {
  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.getElementById('profileDropdown');
  const userProfileDropdown = document.getElementById('userProfileDropdown');

  if (profileToggle && profileDropdown) {
    profileToggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      userProfileDropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      if (!userProfileDropdown.contains(e.target)) {
        userProfileDropdown.classList.remove('show');
      }
    });
  }
});