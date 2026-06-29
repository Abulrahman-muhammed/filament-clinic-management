/**
 * App Module — General UI interactions
 */

const App = (() => {
  /**
   * Initialize general UI features
   */
  function init() {
    initNavbar();
    initScrollSpy();
    initSmoothScroll();
    initAiAssistant();
    initAnimations();
  }

  /**
   * Navbar scroll effect
   */
  function initNavbar() {
    const navbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    const collapseEl = document.getElementById('navbarNav');
    if (collapseEl) {
      collapseEl.querySelectorAll('.nav-link').forEach((link) => {
        link.addEventListener('click', () => {
          const toggler = document.querySelector('.navbar-toggler');
          if (collapseEl.classList.contains('show') && toggler) {
            toggler.click();
          }
        });
      });
    }
  }

  /**
   * Highlight active nav link on scroll
   */
  function initScrollSpy() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const id = entry.target.getAttribute('id');
            navLinks.forEach((link) => {
              link.classList.remove('active');
              if (link.getAttribute('href') === `#${id}`) {
                link.classList.add('active');
              }
            });
          }
        });
      },
      { rootMargin: '-40% 0px -50% 0px', threshold: 0 }
    );

    sections.forEach((section) => observer.observe(section));
  }

  /**
   * Smooth scroll for anchor links
   */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener('click', (e) => {
        const targetId = anchor.getAttribute('href');
        if (targetId === '#' || targetId === '#login' || targetId === '#privacy') return;

        const target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  }

  /**
   * AI Assistant button handler (no backend)
   */
  function initAiAssistant() {
    const btn = document.getElementById('startAiAssistant');
    if (!btn) return;

    btn.addEventListener('click', () => {
      document.getElementById('booking').scrollIntoView({ behavior: 'smooth' });

      setTimeout(() => {
        const aiCard = document.querySelector('.ai-card');
        if (aiCard) {
          aiCard.classList.add('pulse-highlight');
          setTimeout(() => aiCard.classList.remove('pulse-highlight'), 2000);
        }
      }, 600);
    });
  }

  /**
   * Intersection-based fade-in animations
   */
  function initAnimations() {
    const animatedElements = document.querySelectorAll(
      '.stat-card, .service-card, .doctor-card, .ai-card, .contact-info-card'
    );

    animatedElements.forEach((el) => el.classList.add('fade-in-element'));

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    animatedElements.forEach((el) => observer.observe(el));
  }

  return { init };
})();

document.addEventListener('DOMContentLoaded', () => App.init());
