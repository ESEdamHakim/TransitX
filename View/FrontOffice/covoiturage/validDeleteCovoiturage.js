document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".btn.delete");

    deleteButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const idCovoit = this.getAttribute("data-id");

            // Confirm deletion
            if (confirm("Êtes-vous sûr de vouloir supprimer ce trajet ?")) {
                // Send delete request to UserDeleteCovoiturage.php
                fetch("UserDeleteCovoiturage.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `id_covoit=${idCovoit}`,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Remove the deleted covoiturage from the DOM
                            this.closest(".route-card").remove();
                            alert("Trajet supprimé avec succès !");
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