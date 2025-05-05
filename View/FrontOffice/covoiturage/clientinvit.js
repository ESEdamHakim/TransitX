document.addEventListener("DOMContentLoaded", () => {
    const bookButtons = document.querySelectorAll(".book-icon-btn");

    bookButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const covoiturageId = button.getAttribute("data-id-covoiturage");
            const isBooked = button.getAttribute("data-booked") === "true";

            // Log the covoiturageId and isBooked values for debugging
            console.log("Covoiturage ID:", covoiturageId);
            console.log("Is Booked:", isBooked);

            try {
                // Send the request to the server
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

                // Log the raw response for debugging
                console.log("Raw Response:", response);

                // Parse the JSON response
                const result = await response.json();

                // Log the parsed JSON response for debugging
                console.log("Parsed Response:", result);

                if (result.success) {
                    if (isBooked) {
                        button.innerHTML = '<i class="fa-solid fa-plus"></i>';
                        button.setAttribute("data-booked", "false");
                        alert("Votre demande a été annulée.");
                    } else {
                        button.innerHTML = '<i class="fa-solid fa-pause" style="color: #FFD43B;"></i>';
                        button.setAttribute("data-booked", "true");
                        alert("Votre demande a été envoyée.");
                    }
                } else {
                    // Log the error message from the server
                    console.log("Server Error Message:", result.message);
                    console.log("Debug Info:", result.debug); // Log debug info if available
                    alert("Erreur : " + result.message);
                }
            } catch (error) {
                // Log any client-side errors
                console.error("Error:", error);
                alert("Une erreur s'est produite. Veuillez réessayer.");
            }
        });
    });
});