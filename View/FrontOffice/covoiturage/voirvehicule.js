document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("vehicule-modal");
    const closeModal = document.querySelector(".close-modal");

    // Close the modal when the close button is clicked
    if (closeModal) {
        closeModal.addEventListener("click", () => {
            modal.style.display = "none";
        });
    } else {
        console.error("Close button not found in the DOM.");
    }

    // Close the modal when clicking outside of it
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Handle "Voir Véhicule" button clicks
    document.querySelectorAll(".vehicule-icon-btn").forEach((button) => {
        button.addEventListener("click", () => {
            console.log("Voir Véhicule button clicked"); // Debugging log

            const idCovoiturage = button.getAttribute("data-id-covoiturage");
            console.log(`Covoiturage ID: ${idCovoiturage}`); // Debugging log

            // Fetch vehicle details via AJAX
            fetch(`getVehiculeDetails.php?id_covoiturage=${idCovoiturage}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        console.log("Vehicle details fetched successfully:", data); // Debugging log

                        // Populate modal with vehicle details
                        document.getElementById("vehicule-marque").textContent = data.vehicule.marque;
                        document.getElementById("vehicule-modele").textContent = data.vehicule.modele;
                        document.getElementById("vehicule-matricule").textContent = data.vehicule.matricule;
                        document.getElementById("vehicule-couleur").textContent = data.vehicule.couleur;
                        document.getElementById("vehicule-places").textContent = data.vehicule.places;

                        // Set the vehicle photo
                        const photoElement = document.getElementById("vehicule-photo");
                        if (photoElement) {
                            photoElement.src = data.vehicule.photo_vehicule || '../../assets/uploads/default-image.jpg'; // Use a default image if none is provided
                        } else {
                            console.error("Element with id 'vehicule-photo' not found in the DOM.");
                        }

                        // Show the modal
                        modal.style.display = "block";
                    } else {
                        alert(data.message || "Erreur : Impossible de récupérer les détails du véhicule.");
                    }
                })
                .catch((error) => {
                    console.error("Erreur lors de la récupération des détails du véhicule :", error);
                    alert("Erreur : Une erreur est survenue.");
                });
        });
    });
});