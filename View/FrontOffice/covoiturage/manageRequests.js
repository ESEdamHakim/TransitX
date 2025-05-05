document.addEventListener("DOMContentLoaded", () => {
    const requestButtons = document.querySelectorAll(".request-icon-btn");

    requestButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const covoiturageId = button.getAttribute("data-id-covoiturage");
            const userId = button.getAttribute("data-id-user");

            // Fetch user details (you can replace this with an AJAX call if needed)
            fetch(`getUserDetails.php?id_user=${userId}`)
                .then((response) => response.json())
                .then((user) => {
                    const message = `
                        L'utilisateur suivant souhaite rejoindre votre trajet :
                        Nom: ${user.nom}
                        Prénom: ${user.prenom}
                        Email: ${user.email}
                        Téléphone: ${user.telephone}
                    `;

                    const accept = confirm(message + "\nVoulez-vous accepter cette demande ?");
                    if (accept) {
                        alert("Demande acceptée.");
                        button.innerHTML = '<i class="fa-solid fa-check"></i>';
                    } else {
                        alert("Demande refusée.");
                        button.innerHTML = '<i class="fa-solid fa-face-sad-tear" style="color: #dd3308;"></i>';
                    }
                })
                .catch((error) => {
                    console.error("Error fetching user details:", error);
                    alert("Une erreur s'est produite. Veuillez réessayer.");
                });
        });
    });
});