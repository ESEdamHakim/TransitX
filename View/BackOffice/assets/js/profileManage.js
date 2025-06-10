document.addEventListener('DOMContentLoaded', function () {
    // Open View Profile Modal (event delegation, supports dynamic content)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.open-view-profile');
        if (btn) {
            e.preventDefault();
            const userId = btn.getAttribute('data-user-id');
            fetch('view_profile.php?modal=1&id=' + encodeURIComponent(userId))
                .then(res => res.text())
                .then(html => {
                    document.getElementById('viewProfileContent').innerHTML = html;
                    document.getElementById('viewProfileModal').classList.add('active');
                });
        }
    });

    // Open Edit Profile Modal (event delegation)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.open-edit-profile');
        if (btn) {
            e.preventDefault();
            // You can pass user ID if needed, similar to view modal
            fetch('edit_profile.php?modal=1')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('editProfileContent').innerHTML = html;
                    document.getElementById('editProfileModal').classList.add('active');
                });
        }
    });

    // Close modals when clicking outside the modal container
    document.querySelectorAll('.view-profile-modal, .edit-profile-modal').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('active');
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
                if (modal.id === 'viewProfileModal') {
                    document.getElementById('viewProfileContent').innerHTML = '';
                } else if (modal.id === 'editProfileModal') {
                    document.getElementById('editProfileContent').innerHTML = '';
                }
            }
        }
    });

    // AJAX submit for edit profile form in modal
    document.addEventListener('submit', function (e) {
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
                    if (html.includes('profile-update-success')) {
                        setTimeout(() => {
                            document.getElementById('editProfileModal').classList.remove('active');
                            document.getElementById('editProfileContent').innerHTML = '';
                            // Optionally reload the page or a section here
                            window.location.href = 'index.php';
                        }, 800);
                    }
                })
                .catch(err => {
                    document.getElementById('editProfileContent').innerHTML = '<div class="alert error">Erreur lors de la mise à jour.</div>';
                });
        }
    });
    document.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'modifierProfileBtn') {
            // Close the view modal
            document.getElementById('viewProfileModal').classList.remove('active');
            document.getElementById('viewProfileContent').innerHTML = '';
            // Open the edit modal via AJAX
            fetch('edit_profile.php?modal=1')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('editProfileContent').innerHTML = html;
                    document.getElementById('editProfileModal').classList.add('active');
                });
        }
    });
});