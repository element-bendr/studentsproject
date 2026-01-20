// ========================================
// FRONTEND INTERACTIVITY
// All security handled server-side
// ========================================

(function() {
  'use strict';

  // ========================================
  // HAMBURGER MENU TOGGLE
  // ========================================
  const menuToggle = document.getElementById('mobile-menu-toggle');
  const navMenu = document.getElementById('nav-menu');

  if (menuToggle && navMenu) {
    // Toggle menu on hamburger click
    menuToggle.addEventListener('click', function() {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !isExpanded);
      navMenu.classList.toggle('active');
    });

    // Close menu when a link is clicked
    const navLinks = navMenu.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        menuToggle.setAttribute('aria-expanded', 'false');
        navMenu.classList.remove('active');
      });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
      const isClickInside = navMenu.contains(event.target) || menuToggle.contains(event.target);
      if (!isClickInside && navMenu.classList.contains('active')) {
        menuToggle.setAttribute('aria-expanded', 'false');
        navMenu.classList.remove('active');
      }
    });
  }

  // ========================================
  // TABLE FILTER HELPER
  // ========================================
  const filters = document.querySelectorAll('[data-table-filter]');
  filters.forEach(input => {
    const tableId = input.getAttribute('data-table-filter');
    const table = document.getElementById(tableId);
    if (!table) return;
    
    input.addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const rows = table.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  });

  // ========================================
  // FORM HINT INTERACTIVITY
  // ========================================
  const formInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="time"], textarea');
  
  formInputs.forEach(input => {
    const hint = input.nextElementSibling;
    if (hint && hint.classList.contains('form-hint')) {
      input.addEventListener('focus', function() {
        hint.style.color = 'var(--primary)';
      });
      
      input.addEventListener('blur', function() {
        hint.style.color = 'var(--neutral-500)';
      });
    }
  });

  // ========================================
  // MARK CURRENT PAGE IN NAVIGATION
  // ========================================
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });

  console.log('Frontend scripts initialized');
})();
