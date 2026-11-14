(() => {
  'use strict';
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const revealElements = [...document.querySelectorAll('[data-sp-reveal], .sp-enter')];
  revealElements.forEach((element, index) => {
    const siblings = element.parentElement ? [...element.parentElement.children].filter((child) => child.matches('[data-sp-reveal], .sp-enter')) : [];
    element.style.setProperty('--sp-delay', `${Math.max(0, siblings.indexOf(element)) * 75}ms`);
  });
  if (reducedMotion || !('IntersectionObserver' in window)) {
    revealElements.forEach((element) => element.classList.add('is-revealed'));
  } else {
    const observer = new IntersectionObserver((entries, instance) => entries.forEach((entry) => {
      if (entry.isIntersecting) { entry.target.classList.add('is-revealed'); instance.unobserve(entry.target); }
    }), { threshold: .08, rootMargin: '0px 0px -24px 0px' });
    revealElements.forEach((element) => observer.observe(element));
  }

  const toastRegion = () => {
    let region = document.getElementById('spToastRegion');
    if (!region) {
      region = document.createElement('div');
      region.id = 'spToastRegion'; region.className = 'sp-toast-region'; region.setAttribute('role', 'status'); region.setAttribute('aria-live', 'polite');
      document.body.append(region);
    }
    return region;
  };
  window.spToast = (message, options = {}) => {
    const toast = document.createElement('div');
    toast.className = `sp-toast${options.variant === 'danger' ? ' sp-toast--danger' : ''}`;
    const text = document.createElement('span'); text.textContent = String(message); toast.append(text);
    const close = document.createElement('button'); close.type = 'button'; close.className = 'sp-toast__close'; close.setAttribute('aria-label', 'Dismiss notification'); close.textContent = '×';
    close.addEventListener('click', () => toast.remove()); toast.append(close); toastRegion().append(toast);
    const duration = Number.isFinite(options.duration) ? Math.max(0, options.duration) : 4200;
    if (duration) window.setTimeout(() => toast.remove(), duration);
    return toast;
  };

  window.spTooltip = (target, message) => {
    const element = typeof target === 'string' ? document.querySelector(target) : target;
    if (!element) return null;
    element.dataset.spTooltip = String(message);
    element.title = String(message);
    if (!element.hasAttribute('aria-label') && !element.textContent.trim()) element.setAttribute('aria-label', String(message));
    return element;
  };
  document.querySelectorAll('[data-sp-tooltip]').forEach((element) => {
    if (!element.title) element.title = element.dataset.spTooltip;
  });

  document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-sp-modal-open]');
    if (openButton) {
      const modal = document.getElementById(openButton.dataset.spModalOpen);
      if (modal instanceof HTMLDialogElement && !modal.open) modal.showModal();
    }
    const closeButton = event.target.closest('[data-sp-modal-close]');
    if (closeButton) closeButton.closest('dialog.sp-modal')?.close();
    const toastButton = event.target.closest('[data-sp-toast]');
    if (toastButton) window.spToast(toastButton.dataset.spToast, { variant: toastButton.dataset.spToastVariant });
  });
})();
