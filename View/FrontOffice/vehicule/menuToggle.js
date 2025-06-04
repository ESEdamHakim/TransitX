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
});