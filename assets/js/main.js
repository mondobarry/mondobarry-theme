/* MondoBarry Portfolio — main.js */
(function() {
  'use strict';

  // ── Scroll Reveal ──────────────────────────────────
  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          const delay = entry.target.dataset.delay || 0;
          setTimeout(() => {
            entry.target.classList.add('visible');
          }, delay);
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    // Stagger children in grids
    document.querySelectorAll('.skills-grid, .projects-grid, .infra-grid').forEach(grid => {
      grid.querySelectorAll('.reveal').forEach((el, i) => {
        el.dataset.delay = i * 80;
      });
    });

    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('visible'));
  }

  // ── Nav Hamburger ──────────────────────────────────
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('nav-links');
  if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      const spans = hamburger.querySelectorAll('span');
      if (navLinks.classList.contains('open')) {
        spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        spans[1].style.opacity = '0';
        spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
      } else {
        spans[0].style.transform = '';
        spans[1].style.opacity = '';
        spans[2].style.transform = '';
      }
    });
    // Close on link click
    navLinks.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        navLinks.classList.remove('open');
        hamburger.querySelectorAll('span').forEach(s => s.style = '');
      });
    });
  }

  // ── Nav scroll background ──────────────────────────
  const nav = document.getElementById('mb-nav');
  if (nav) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) {
        nav.style.background = 'rgba(7,9,15,0.98)';
        nav.style.boxShadow  = '0 1px 24px rgba(0,0,0,0.5)';
      } else {
        nav.style.background = '';
        nav.style.boxShadow  = '';
      }
    }, { passive: true });
  }

  // ── Smooth active nav links ────────────────────────
  const sections = document.querySelectorAll('section[id]');
  const navAnchors = document.querySelectorAll('#nav-links a[href^="#"]');
  if (sections.length && navAnchors.length) {
    const sectionObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          navAnchors.forEach(a => {
            a.style.color = '';
            if (a.getAttribute('href') === '#' + entry.target.id) {
              if (!a.classList.contains('nav-cta')) a.style.color = '#E2E8F0';
            }
          });
        }
      });
    }, { threshold: 0.4 });
    sections.forEach(s => sectionObserver.observe(s));
  }


  // ── Formulaire de contact AJAX ─────────────────────
  const form = document.getElementById('mb-contact-form');
  if (form && typeof mbAjax !== 'undefined') {
    form.addEventListener('submit', async function(e) {
      e.preventDefault();
      const btnText    = form.querySelector('.btn-text');
      const btnLoading = form.querySelector('.btn-loading');
      const result     = document.getElementById('mb-form-result');
      const submit     = form.querySelector('.btn-submit');

      submit.disabled = true;
      btnText.style.display    = 'none';
      btnLoading.style.display = 'inline';
      result.style.display     = 'none';
      result.className         = 'form-result';

      const data = new FormData();
      data.append('action',     'mb_contact');
      data.append('nonce',      mbAjax.nonce);
      data.append('mb_name',    form.querySelector('#mb_name').value);
      data.append('mb_email',   form.querySelector('#mb_email').value);
      data.append('mb_subject', form.querySelector('#mb_subject')?.value || '');
      data.append('mb_message', form.querySelector('#mb_message').value);

      try {
        const resp = await fetch(mbAjax.url, { method: 'POST', body: data });
        const json = await resp.json();
        result.style.display = 'block';
        if (json.success) {
          result.classList.add('success');
          result.textContent = json.data;
          form.reset();
        } else {
          result.classList.add('error');
          result.textContent = json.data || 'Une erreur est survenue.';
        }
      } catch(err) {
        result.style.display = 'block';
        result.classList.add('error');
        result.textContent = 'Erreur réseau. Veuillez réessayer.';
      } finally {
        submit.disabled = false;
        btnText.style.display    = 'inline';
        btnLoading.style.display = 'none';
      }
    });
  }

})();
