document.addEventListener("DOMContentLoaded", () => {
    const userButtons = document.querySelectorAll(".user-icon-btn");
    const userModal = document.getElementById("user-modal");
    const closeUserModal = document.querySelector(".close-user-modal");

    userButtons.forEach(button => {
        button.addEventListener("click", async () => {
            const id_user = button.getAttribute("data-id-user");

            try {
                const response = await fetch(`driverprofile.php?action=getUserById&id_user=${id_user}`);
                const user = await response.json();

                if (user) {
                    document.getElementById("user-nom").textContent = user.nom;
                    document.getElementById("user-prenom").textContent = user.prenom;
                    document.getElementById("user-email").textContent = user.email;
                    document.getElementById("user-telephone").textContent = user.telephone;
                    document.getElementById("user-image").src = user.image
    ? '../../../Controller/get_image.php?file=' + encodeURIComponent(user.image)
    : '../../../Controller/get_image.php?file=default.png';
                    userModal.style.display = "block";
                } else {
                    alert("Utilisateur introuvable.");
                }
            } catch (error) {
                console.error("Erreur lors de la récupération des informations utilisateur:", error);
            }
        });
    });

    closeUserModal.addEventListener("click", () => {
        userModal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === userModal) {
            userModal.style.display = "none";
        }
    });
});