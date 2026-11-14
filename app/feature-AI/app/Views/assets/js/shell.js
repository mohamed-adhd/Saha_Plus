(() => {
  'use strict';

  // Hide the spinner (front) / preloader (back) once the page has loaded.
  const hideLoaders = () => {
    document.querySelectorAll('#spinner, #preloader').forEach((el) => el.classList.add('is-hidden'));
  };
  if (document.readyState === 'complete') { hideLoaders(); }
  else { window.addEventListener('load', hideLoaders); setTimeout(hideLoaders, 4000); }

  // Back to top button (front office footer).
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    const toggle = () => { backToTop.hidden = window.scrollY < 300; };
    window.addEventListener('scroll', toggle, { passive: true });
    backToTop.addEventListener('click', (event) => {
      event.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    toggle();
  }
})();
