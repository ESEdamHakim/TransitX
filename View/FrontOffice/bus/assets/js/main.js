// Mobile menu toggle
document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
  document.querySelector('.main-nav').classList.toggle('active');
});

// FAQ toggle
const faqItems = document.querySelectorAll('.faq-item');
faqItems.forEach(item => {
  const question = item.querySelector('.faq-question');
  question.addEventListener('click', () => {
    item.classList.toggle('active');
  });
});

// Ensure dashboard button is visible
document.querySelector('.dashboard-btn').style.display = 'inline-flex';
document.querySelector('.logout-btn').style.display = 'inline-flex';

document.addEventListener('DOMContentLoaded', function () {
  // Toggle bus extra info (if using div-based extra info instead of modal)
  const infoButtons = document.querySelectorAll('.toggle-info-btn');

  infoButtons.forEach(button => {
    button.addEventListener('click', function () {
      const trajetId = this.getAttribute('data-id');
      const modal = document.getElementById('bus-info-modal-' + trajetId);
      if (modal) modal.style.display = 'block';
    });
  });

  // Close modal when the close button is clicked
  const closeButtons = document.querySelectorAll('.modal .close-btn');
  closeButtons.forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      modal.style.display = 'none';
    });
  });

  // Close modal when clicking outside of it
  window.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  });
});

// When clicking the "Rechercher" button
document.getElementById('searchForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const arrivalInput = document.getElementById('arrival').value.trim().toLowerCase();
  const startTime = document.getElementById('start-time').value.trim();
  const endTime = document.getElementById('end-time').value.trim();

  function timeToMinutes(timeStr) {
    if (!timeStr) return null;
    const parts = timeStr.split(':').map(Number);
    if (parts.length >= 2) {
      const [hours, minutes] = parts;
      return hours * 60 + minutes;
    }
    return null;
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


