document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("vehicule-modal");
    const closeModal = document.querySelector(".close-modal");

    // Close the modal when the close button is clicked
    closeModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close the modal when clicking outside of it
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Handle "Voir Véhicule" button clicks
    document.querySelectorAll(".voir-vehicule-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const idCovoiturage = button.getAttribute("data-id-covoiturage");

            // Fetch vehicle details via AJAX
            fetch(`getVehiculeDetails.php?id_covoiturage=${idCovoiturage}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Populate modal with vehicle details
                        document.getElementById("vehicule-marque").textContent = data.vehicule.marque;
                        document.getElementById("vehicule-modele").textContent = data.vehicule.modele;
                        document.getElementById("vehicule-matricule").textContent = data.vehicule.matricule;
                        document.getElementById("vehicule-couleur").textContent = data.vehicule.couleur;
                        document.getElementById("vehicule-places").textContent = data.vehicule.places;

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