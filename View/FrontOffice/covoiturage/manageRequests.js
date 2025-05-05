document.addEventListener("DOMContentLoaded", () => {
    const requestButtons = document.querySelectorAll(".request-icon-btn");

    requestButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const covoiturageId = button.getAttribute("data-id-covoiturage");
            const userId = button.getAttribute("data-id-user");

            try {
                // Fetch user details from the server
                const response = await fetch(`getUserDetails.php?id_user=${userId}`);
                const result = await response.json();

                console.log("User Details Response:", result); // Log the response for debugging

                if (result.success) {
                    const user = result.data;

                    // Display user details in a confirmation dialog
                    const message = `
                        L'utilisateur suivant souhaite rejoindre votre trajet :
                        Nom: ${user.nom}
                        Prénom: ${user.prenom}
                        Email: ${user.email}
                        Téléphone: ${user.telephone}
                    `;

                    const accept = confirm(message + "\nVoulez-vous accepter cette demande ?");
                    const action = accept ? "accept" : "reject";

                    // Send the response to the server
                    const actionResponse = await fetch("updateBookingStatus.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            covoiturageId,
                            userId,
                            action,
                        }),
                    });

                    const actionResult = await actionResponse.json();

                    if (actionResult.success) {
                        if (accept) {
                            alert("Demande acceptée.");
                            button.innerHTML = '<i class="fa-solid fa-check" style="color: #aaec98;"></i>';
                        } else {
                            alert("Demande refusée.");
                            button.innerHTML = '<i class="fa-solid fa-face-sad-tear" style="color: #dd3308;"></i>';
                        }
                        // Disable the button after responding
                        button.disabled = true;
                    } else {
                        alert("Erreur : " + actionResult.message);
                    }
                } else {
                    alert("Erreur : " + result.message);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Une erreur s'est produite. Veuillez réessayer.");
            }
        });
    });
});