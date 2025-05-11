document.addEventListener("DOMContentLoaded", () => {
    // Handle booking and canceling requests
    const bookButtons = document.querySelectorAll(".book-icon-btn");

    bookButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const covoiturageId = button.getAttribute("data-id-covoiturage");
            const isBooked = button.getAttribute("data-booked") === "true";

            try {
                // Send the booking or cancel request to the server
                const response = await fetch("bookCovoiturage.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        covoiturageId: covoiturageId,
                        action: isBooked ? "cancel" : "book",
                    }),
                });
                console.log("Raw Response:", response);
                // Check if the response is ok
                const result = await response.json();
                console.log("Parsed Result:", result);
                if (result.success) {
                    // Update the button state
                    if (isBooked) {
                        button.innerHTML = '<i class="fa-solid fa-plus"></i>';
                        button.setAttribute("data-booked", "false");
                    } else {
                        button.innerHTML = '<i class="fa-solid fa-pause" style="color: #FFD43B;"></i>';
                        button.setAttribute("data-booked", "true");
                    }
                } else {
                    console.error("Error:", result.message);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Une erreur s'est produite lors de la communication avec le serveur.");
            }
        });
    });

    // Handle responding to booking requests
    const requestButtons = document.querySelectorAll(".request-icon-btn");
    const clientModal = document.getElementById("client-booked-modal");
    const closeClientModalButton = document.querySelector(".close-client-booked-modal");
    const acceptClientButton = document.querySelector(".accept-client-request");
    const rejectClientButton = document.querySelector(".reject-client-request");

    requestButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const covoiturageId = button.getAttribute("data-id-covoiturage");
            const id_user = button.getAttribute("data-id-user");

            try {
                // Fetch user details from the server
                const response = await fetch(`getUserDetails.php?id_user=${id_user}`);
                const result = await response.json();

                if (result.success) {
                    const user = result.data;

                    // Populate the modal with user details
                    document.getElementById("client-nom").textContent = user.nom;
                    document.getElementById("client-prenom").textContent = user.prenom;
                    document.getElementById("client-email").textContent = user.email;
                    document.getElementById("client-telephone").textContent = user.telephone;

                    // Show the modal
                    clientModal.style.display = "block";

                    // Handle accept and reject actions
                    acceptClientButton.onclick = async () => {
                        await handleRequestAction(covoiturageId, id_user, "accept");
                        clientModal.style.display = "none";
                    };

                    rejectClientButton.onclick = async () => {
                        await handleRequestAction(covoiturageId, id_user, "reject");
                        clientModal.style.display = "none";
                    };
                } else {
                    console.error("Error:", result.message);
                }
            } catch (error) {
                console.error("Error:", error);
            }
        });
    });

    // Close the modal when the close button is clicked
    closeClientModalButton.addEventListener("click", () => {
        clientModal.style.display = "none";
    });

    // Function to handle accept/reject actions
    async function handleRequestAction(covoiturageId, id_user, action) {
        try {
            const response = await fetch("updateBookingStatus.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    covoiturageId,
                    id_user,
                    action,
                }),
            });

            const result = await response.json();

            if (result.success) {
                console.log(action === "accept" ? "Demande acceptée." : "Demande refusée.");
                // Removed alert here
            } else {
                console.error("Error:", result.message);
                // Removed alert here
            }
        } catch (error) {
            console.error("Error:", error);
            // Removed alert here
        }
    }
});