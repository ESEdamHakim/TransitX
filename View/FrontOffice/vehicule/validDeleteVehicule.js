document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".btn.delete");

    deleteButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const idVehicule = this.getAttribute("data-id");

            // Confirm deletion
            if (confirm("Êtes-vous sûr de vouloir supprimer ce véhicule ?")) {
                // Send delete request to UserDeleteVehicule.php
                fetch("DeleteVehicule.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `id_vehicule=${idVehicule}`,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Remove the deleted vehicle from the DOM
                            this.closest(".vehicle-card").remove();
                            alert("Véhicule supprimé avec succès !");
                        } else {
                            alert("Erreur : " + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Erreur lors de la suppression :", error);
                        alert("Une erreur est survenue lors de la suppression.");
                    });
            }
        });
    });
});