document.addEventListener("DOMContentLoaded", () => {
    const bookButtons = document.querySelectorAll(".book-icon-btn");

    bookButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const covoiturageId = button.getAttribute("data-id-covoiturage");
            const userId = button.getAttribute("data-id-user");
            const isBooked = button.getAttribute("data-booked") === "true";

            try {
                // Send booking/canceling request to the server
                const response = await fetch("bookCovoiturage.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ covoiturageId, userId, action: isBooked ? "cancel" : "book" }),
                });

                const result = await response.json();

                if (result.success) {
                    // Toggle button state
                    if (isBooked) {
                        button.innerHTML = '<i class="fa-solid fa-plus"></i>';
                        button.setAttribute("data-booked", "false");
                        alert("Votre demande a été annulée.");
                    } else {
                        button.innerHTML = '<i class="fa-regular fa-circle-pause" style="color: #FFD43B;"></i>';
                        button.setAttribute("data-booked", "true");
                        alert("Votre demande a été envoyée. Veuillez attendre l'acceptation.");
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