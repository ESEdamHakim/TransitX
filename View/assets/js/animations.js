// animations.js

document.addEventListener("DOMContentLoaded", () => {
    // 1. Fade in the whole page on load
    document.body.style.opacity = 0;
    document.body.style.transition = "opacity 0.8s ease-in-out";
    requestAnimationFrame(() => {
      document.body.style.opacity = 1;
    });
  
    // 2. Animate elements on scroll
    const scrollElems = document.querySelectorAll(".scroll-animate");
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("animate-in");
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
  
    scrollElems.forEach(el => observer.observe(el));
  
    // 3. Button hover animation
    const buttons = document.querySelectorAll(".animated-button");
    buttons.forEach(btn => {
      btn.style.transition = "transform 0.2s ease, box-shadow 0.2s ease";
      btn.addEventListener("mouseover", () => {
        btn.style.transform = "scale(1.05)";
        btn.style.boxShadow = "0 8px 16px rgba(0,0,0,0.2)";
      });
      btn.addEventListener("mouseout", () => {
        btn.style.transform = "scale(1)";
        btn.style.boxShadow = "none";
      });
    });
  
    // 4. Form input animation on focus
    const inputs = document.querySelectorAll("input, textarea, select");
    inputs.forEach(input => {
      input.addEventListener("focus", () => {
        input.style.boxShadow = "0 0 8px rgba(151, 195, 162, 0.6)";
        input.style.transition = "box-shadow 0.3s ease-in-out";
      });
      input.addEventListener("blur", () => {
        input.style.boxShadow = "none";
      });
    });
  });
  