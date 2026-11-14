(() => {
  'use strict';
  const sidebar = document.getElementById('spSidebar');
  if (!sidebar) return;

  const pin = document.getElementById('spSidebarPin');
  const openButton = document.getElementById('spSidebarOpen');
  const closeButton = document.getElementById('spSidebarClose');
  const backdrop = document.getElementById('spSidebarBackdrop');
  const nav = sidebar.querySelector('.sp-sidebar__nav');
  let touchStartX = null;

  const readPinned = () => { try { return localStorage.getItem('sp-sidebar-pinned') === 'true'; } catch (_) { return false; } };
  const pinned = readPinned();
  sidebar.classList.toggle('is-pinned', pinned);
  pin.setAttribute('aria-pressed', String(pinned));

  const updateIndicator = () => {
    const active = nav.querySelector('.sp-nav-item.is-active');
    const indicator = nav.querySelector('.sp-sidebar__indicator');
    if (!active || !indicator) return;
    indicator.style.transform = `translateY(${active.offsetTop}px)`;
    indicator.style.height = `${active.offsetHeight}px`;
    nav.classList.add('has-active');
  };
  const closeDrawer = (restoreFocus = true) => {
    sidebar.classList.remove('is-open');
    backdrop.classList.remove('is-visible');
    backdrop.hidden = true;
    openButton.setAttribute('aria-expanded', 'false');
    if (window.innerWidth < 768) {
      sidebar.inert = true;
      sidebar.setAttribute('aria-hidden', 'true');
      if (restoreFocus) openButton.focus();
    }
  };
  const openDrawer = () => {
    sidebar.inert = false;
    sidebar.removeAttribute('aria-hidden');
    sidebar.classList.add('is-open');
    backdrop.hidden = false;
    requestAnimationFrame(() => backdrop.classList.add('is-visible'));
    openButton.setAttribute('aria-expanded', 'true');
    closeButton.focus();
  };
  const syncViewport = () => {
    const mobile = window.innerWidth < 768;
    if (mobile && !sidebar.classList.contains('is-open')) {
      sidebar.inert = true;
      sidebar.setAttribute('aria-hidden', 'true');
    } else {
      sidebar.inert = false;
      sidebar.removeAttribute('aria-hidden');
    }
    if (!mobile && sidebar.classList.contains('is-open')) closeDrawer(false);
    updateIndicator();
  };

  pin.addEventListener('click', () => {
    const next = !sidebar.classList.contains('is-pinned');
    sidebar.classList.toggle('is-pinned', next);
    pin.setAttribute('aria-pressed', String(next));
    try { localStorage.setItem('sp-sidebar-pinned', String(next)); } catch (_) { /* State remains active until navigation. */ }
  });
  openButton.addEventListener('click', openDrawer);
  closeButton.addEventListener('click', () => closeDrawer());
  backdrop.addEventListener('click', () => closeDrawer());
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && sidebar.classList.contains('is-open')) closeDrawer(); });
  sidebar.addEventListener('touchstart', (event) => { touchStartX = event.changedTouches[0].clientX; }, { passive: true });
  sidebar.addEventListener('touchend', (event) => {
    if (touchStartX !== null && touchStartX - event.changedTouches[0].clientX > 55) closeDrawer();
    touchStartX = null;
  }, { passive: true });
  window.addEventListener('resize', syncViewport);
  requestAnimationFrame(syncViewport);
})();
