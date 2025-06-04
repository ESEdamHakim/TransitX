document.addEventListener('DOMContentLoaded', function () {

  // Mobile menu toggle
  const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function () {
      const mainNav = document.querySelector('.main-nav');
      if (mainNav) {
        mainNav.classList.toggle('active');
      }
    });
  }

  // FAQ toggle
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    if (question) {
      question.addEventListener('click', () => {
        item.classList.toggle('active');
      });
    }
  });

  // Dashboard & logout button visibility (safe check)
  const dashboardBtn = document.querySelector('.dashboard-btn');
  if (dashboardBtn) {
    dashboardBtn.style.display = 'inline-flex';
  }

  const logoutBtn = document.querySelector('.logout-btn');
  if (logoutBtn) {
    logoutBtn.style.display = 'inline-flex';
  }

  // Toggle bus extra info (modal version)
  const infoButtons = document.querySelectorAll('.toggle-info-btn');
  infoButtons.forEach(button => {
    button.addEventListener('click', function () {
      const trajetId = this.getAttribute('data-id');
      const modal = document.getElementById('bus-info-modal-' + trajetId);
      if (modal) {
        modal.classList.add('show');
      }
    });
  });

  // Close modal when the close button is clicked
  const closeButtons = document.querySelectorAll('.modal .close-btn');
  closeButtons.forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      if (modal) {
        modal.classList.remove('show');
      }
    });
  });

  // Close modal when clicking outside of it
  window.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) {
        modal.classList.remove('show');
      }
    });
  });

  // Search filtering
  const searchForm = document.getElementById('searchForm');
  if (searchForm) {
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const arrivalInput = document.getElementById('arrival').value.trim().toLowerCase();
      const startTime = document.getElementById('start-time').value.trim();
      const endTime = document.getElementById('end-time').value.trim();

      function timeToMinutes(timeStr) {
        if (!timeStr) return null;
        const parts = timeStr.split(':').map(Number);
        return parts.length >= 2 ? parts[0] * 60 + parts[1] : null;
      }

      const startMinutes = timeToMinutes(startTime);
      const endMinutes = timeToMinutes(endTime);

      const cards = document.querySelectorAll('.route-card');
      cards.forEach(card => {
        const arrivalElem = card.querySelector('.arrival');
        const timeElem = card.querySelector('.route-details .detail:first-child span');

        const arrivalText = arrivalElem ? arrivalElem.innerText.trim().toLowerCase() : '';
        const timeText = timeElem ? timeElem.innerText.trim().substring(0, 5) : '';

        let show = true;

        if (arrivalInput && !arrivalText.includes(arrivalInput)) {
          show = false;
        }

        if (startMinutes !== null && endMinutes !== null && timeText) {
          const trajetMinutes = timeToMinutes(timeText);
          if (trajetMinutes === null || trajetMinutes < startMinutes || trajetMinutes > endMinutes) {
            show = false;
          }
        }

        card.style.display = show ? '' : 'none';
      });
    });
  }

  // Favoris toggle
  const favorisBtns = document.querySelectorAll('.favoris-btn');
  favorisBtns.forEach(button => {
    button.addEventListener('click', function () {
      const trajetId = this.dataset.trajetId;
      const isFavorited = this.classList.contains('favorited');
      const action = isFavorited ? 'remove' : 'add';

      fetch('toggle_favoris.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_trajet=${trajetId}&action=${action}`
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.classList.toggle('favorited');
            location.reload(); // Refresh to reflect updated data
          } else {
            alert("Erreur : " + data.message);
          }
        });
    });
  });

  // Modal open/close helpers
  function openModal(modalId, message = '', title = '') {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    if (modalId === 'errorModal') {
      const errorMessageElement = document.getElementById('errorMessage');
      if (errorMessageElement) errorMessageElement.innerText = message;
    }

    if (modalId === 'successModal') {
      const successMessageElement = document.getElementById('successMessage');
      const modalTitleElement = document.getElementById('modalTitle');
      if (successMessageElement) successMessageElement.innerText = message;
      if (modalTitleElement) modalTitleElement.innerText = title || 'Succès';
    }

    modal.classList.add('show');
    // Initialize Card.js (Jesse Pollak) when modal is opened
    if (modalId === 'creditCardModal' && window.Card) {
      if (!window.cardInstance) {
        window.cardInstance = new Card({
          form: '#creditCardForm',
          container: '#card-wrapper',
          formSelectors: {
            numberInput: 'input[name="number"]',
            expiryInput: 'input[name="expiry"]',
            cvcInput: 'input[name="cvc"]',
            nameInput: 'input[name="name"]'
          }
        });
      }
    }
  }

  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('show');
    }
  }

  // Update bus seat count
  function updateNbPlacesDispo(busId, delta) {
    const dispoElement = document.querySelector(`.nbplacesdispo[data-bus-id="${busId}"]`);
    if (dispoElement) {
      const currentValue = parseInt(dispoElement.textContent);
      if (!isNaN(currentValue)) {
        dispoElement.textContent = currentValue + delta;
      }
    }
  }

  // Reservation & cancellation (delegated event handling) with payment choice
  let pendingReservation = null;

  document.addEventListener('click', function (event) {
    const target = event.target;

    // Intercept reservation button to show payment choice modal
    if (target.classList.contains('reserver-btn')) {
      event.preventDefault();
      pendingReservation = {
        busId: target.getAttribute('data-bus-id'),
        busNum: target.getAttribute('data-bus-num'),
        button: target
      };
      openModal('paymentChoiceModal');
      return;
    }

    // Annuler button logic remains unchanged
    if (target.classList.contains('annuler-btn')) {
      event.preventDefault();
      const busId = target.getAttribute('data-bus-id');
      const busNum = target.getAttribute('data-bus-num');

      fetch('annuler_reservation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_bus=${busId}`
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            openModal('successModal', `Votre réservation pour le bus ${busNum} a été annulée.`, 'Annulation réussie !');
            target.outerHTML = `<button class="reserver-btn" data-bus-id="${busId}" data-bus-num="${busNum}">Réserver ce bus</button>`;
            updateNbPlacesDispo(busId, 1);
          } else {
            openModal('errorModal', data.message);
          }
        });
    }
  });

  // Payment choice buttons
  const payByCardBtn = document.getElementById('payByCardBtn');
  const payByCashBtn = document.getElementById('payByCashBtn');

  if (payByCardBtn) {
payByCardBtn.addEventListener('click', function () {
  closeModal('paymentChoiceModal');
  openModal('creditCardModal');
  setTimeout(() => {
    const creditCardForm = document.getElementById('creditCardForm');
    if (creditCardForm) {
      creditCardForm.reset();
      // Optionally clear Card.js UI as well
      const cardInputs = creditCardForm.querySelectorAll('input');
      cardInputs.forEach(input => input.value = '');
      // ... re-attach submit handler ...
    }
  }, 100);
});
  }

  if (payByCashBtn) {
    payByCashBtn.addEventListener('click', function () {
      closeModal('paymentChoiceModal');
      // Simulate reservation by cash
      if (pendingReservation) {
        fetch('reserver_bus.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id_bus=${pendingReservation.busId}&payment=cash`
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              openModal('successModal', `Votre réservation (paiement en espèces) a été confirmée pour le bus ${pendingReservation.busNum}.`, 'Réservation réussie !');
              if (pendingReservation.button) {
                pendingReservation.button.outerHTML = `<button class="annuler-btn" data-bus-id="${pendingReservation.busId}" data-bus-num="${pendingReservation.busNum}">Annuler la réservation</button>`;
              }
              updateNbPlacesDispo(pendingReservation.busId, -1);
            } else {
              openModal('errorModal', data.message);
            }
            pendingReservation = null;
          });
      }
    });
  }

  // Handle credit card form submission
  const creditCardForm = document.getElementById('creditCardForm');
  if (creditCardForm) {
    creditCardForm.addEventListener('submit', function (e) {
      e.preventDefault();
      // You can add Card.js validation here if you want
      if (pendingReservation) {
        fetch('reserver_bus.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id_bus=${pendingReservation.busId}&payment=card`
        })
          .then(response => response.json())
          .then(data => {
            closeModal('creditCardModal');
            if (data.success) {
              openModal('successModal', `Votre réservation a été confirmée pour le bus ${pendingReservation.busNum}.`, 'Réservation réussie !');
              if (pendingReservation.button) {
                pendingReservation.button.outerHTML = `<button class="annuler-btn" data-bus-id="${pendingReservation.busId}" data-bus-num="${pendingReservation.busNum}">Annuler la réservation</button>`;
              }
              updateNbPlacesDispo(pendingReservation.busId, -1);
            } else {
              openModal('errorModal', data.message);
            }
            pendingReservation = null;
          });
      }
    });
  }

});