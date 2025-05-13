document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('vehicule-modal');
    const closeBtn = document.querySelector('.close-modal');

    document.querySelectorAll('.voir-vehicule-btn, .vehicule-icon-btn').forEach(button => {
        button.addEventListener('click', function () {
const idCovoit = this.dataset.idCovoiturage;

             fetch(`getVehiculeDetails.php?id_covoiturage=${idCovoit}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                         console.log("Vehicle details fetched successfully:", data); 
                        document.getElementById('vehicule-marque').textContent = data.vehicule.marque || 'N/A';
                        document.getElementById('vehicule-modele').textContent = data.vehicule.modele || 'N/A';
                        document.getElementById('vehicule-matricule').textContent = data.vehicule.matricule || 'N/A';
                        document.getElementById('vehicule-couleur').textContent = data.vehicule.couleur || 'N/A';
                        document.getElementById('vehicule-places').textContent =data.vehicule.nb_places || 'N/A';
                        document.getElementById('vehicule-photo').src = data.vehicule.photo || '';

                        const photoElement = document.getElementById("vehicule-photo");
                        if (photoElement) {
                            photoElement.src = data.vehicule.photo_vehicule || '../../assets/uploads/default-image.jpg'; // Use a default image if none is provided
                        } else {
                            console.error("Element with id 'vehicule-photo' not found in the DOM.");
                        }

                        // Show the modal
                        modal.style.display = "block";
                    } else {
                        alert('Aucune information sur le véhicule.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement du véhicule :', error);
                });
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', event => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});
