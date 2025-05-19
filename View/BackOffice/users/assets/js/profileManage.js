document.addEventListener('DOMContentLoaded', function () {
    // View Profile
    document.querySelectorAll('.open-view-profile').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('view_profile.php?modal=1')
                .then(res => res.text())
                .then(html => {
                    // Only inject the inner profile content
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

    // Close modals when clicking outside the modal container
    document.querySelectorAll('.view-profile-modal, .edit-profile-modal').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
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

    // Close modals when clicking the close button (event delegation)
    document.addEventListener('click', function (e) {
        if (
            e.target.classList.contains('view-profile-modal-close-btn') ||
            e.target.classList.contains('edit-profile-modal-close-btn')
        ) {
            const modal = e.target.closest('.view-profile-modal, .edit-profile-modal');
            if (modal) {
                modal.classList.remove('active');
                // Clear content for next open
                if (modal.id === 'viewProfileModal') {
                    document.getElementById('viewProfileContent').innerHTML = '';
                } else if (modal.id === 'editProfileModal') {
                    document.getElementById('editProfileContent').innerHTML = '';
                }
            }
        }
    });
    document.addEventListener('submit', function (e) {
        // Only handle the edit profile form inside the modal
        if (e.target.closest('#editProfileModal')) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('edit_profile.php?modal=1', {
                method: 'POST',
                body: formData
            })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('editProfileContent').innerHTML = html;

                    // Close modal if update was successful
                    if (html.includes('profile-update-success')) {
                        setTimeout(() => {
                            document.getElementById('editProfileModal').classList.remove('active');
                            document.getElementById('editProfileContent').innerHTML = '';
                            // Optionally reload the page or a section here
                            window.location.href = 'crud.php';
                        }, 800); // Delay to show success message
                    }
                })
                .catch(err => {
                    document.getElementById('editProfileContent').innerHTML = '<div class="alert error">Erreur lors de la mise à jour.</div>';
                });
        }
    });
});