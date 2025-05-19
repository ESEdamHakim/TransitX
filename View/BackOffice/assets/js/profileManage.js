document.addEventListener('DOMContentLoaded', function () {
    // View Profile
    document.querySelectorAll('.open-view-profile').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            // Example for view profile
            fetch('view_profile.php?modal=1')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('viewProfileContent').innerHTML = html;
                    document.getElementById('viewProfileModal').classList.add('active');
                });
        });
    });

    // Edit Profile
    document.querySelectorAll('.open-edit-profile').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('edit_profile.php?modal=1')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('editProfileContent').innerHTML = html;
                    document.getElementById('editProfileModal').classList.add('active');
                });
        });
    });

    // Close modals when clicking outside modal-content
    document.querySelectorAll('.view-profile-modal, .edit-profile-modal').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.classList.remove('active');
        });
    });
    document.addEventListener('click', function (e) {
        if (
            e.target.classList.contains('close-modal') ||
            e.target.classList.contains('view-profile-modal-close-btn') ||
            e.target.classList.contains('edit-profile-modal-close-btn')
        ) {
            const modal = e.target.closest('.view-profile-modal, .edit-profile-modal');
            modal.classList.remove('active');
            // Clear content for next open
            if (modal.id === 'viewProfileModal') {
                document.getElementById('viewProfileContent').innerHTML = '';
            } else if (modal.id === 'editProfileModal') {
                document.getElementById('editProfileContent').innerHTML = '';
            }
        }
    });
});