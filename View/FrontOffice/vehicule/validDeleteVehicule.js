document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".btn.delete");

    deleteButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const idVehicule = this.getAttribute("data-id");

            // Confirm deletion
            if (confirm("Êtes-vous sûr de vouloir supprimer ce véhicule ?")) {
                // Send delete request to DeleteVehicule.php
                fetch("DeleteVehicule.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `id_vehicule=${encodeURIComponent(idVehicule)}`,
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then((data) => {
                        if (data.success) {
                            // Remove the deleted vehicle from the DOM
                            const vehicleCard = this.closest(".vehicle-card");
                            if (vehicleCard) {
                                vehicleCard.remove();
                            }
                            alert("Véhicule supprimé avec succès !");
                        } else {
                            alert("Erreur : " + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Erreur lors de la suppression :", error);
                        alert("Une erreur est survenue lors de la suppression.");
                    });
                    location.reload();
            }
        });
    });
});