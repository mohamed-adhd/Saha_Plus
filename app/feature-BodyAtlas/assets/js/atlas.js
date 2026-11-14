/* Body Atlas – Front Office interactions */
(() => {
  'use strict';

  const toast = (message) => (window.spToast ? window.spToast(message) : alert(message));

  /* ---------- Body atlas: layer toggle + hotspot/card highlight ---------- */
  const atlas = document.querySelector('.atlas');
  if (atlas) {
    const title = document.getElementById('atlasLayerTitle');
    atlas.querySelectorAll('[data-layer-btn]').forEach((btn) => {
      btn.addEventListener('click', () => {
        atlas.dataset.layer = btn.dataset.layerBtn;
        atlas.querySelectorAll('[data-layer-btn]').forEach((b) => {
          const active = b === btn;
          b.classList.toggle('is-active', active);
          b.setAttribute('aria-selected', String(active));
        });
        if (title) title.textContent = btn.textContent.trim();
      });
    });

    // Hovering a hotspot highlights its card, and the other way round.
    const setHighlight = (slug, on) => {
      atlas.querySelectorAll(`[data-organ="${slug}"], [data-organ-card="${slug}"]`).forEach((el) => el.classList.toggle('is-highlighted', on));
    };
    atlas.querySelectorAll('[data-organ], [data-organ-card]').forEach((el) => {
      const slug = el.dataset.organ || el.dataset.organCard;
      el.addEventListener('mouseenter', () => setHighlight(slug, true));
      el.addEventListener('mouseleave', () => setHighlight(slug, false));
      el.addEventListener('focus', () => setHighlight(slug, true));
      el.addEventListener('blur', () => setHighlight(slug, false));
    });

    // Native tooltip with the organ name on each hotspot.
    atlas.querySelectorAll('.atlas-hotspot').forEach((spot) => {
      const t = document.createElementNS('http://www.w3.org/2000/svg', 'title');
      t.textContent = spot.getAttribute('aria-label');
      spot.prepend(t);
    });
  }

  /* ---------- Organ page: symptom explorer ---------- */
  const summary = document.getElementById('symptomSummary');
  if (summary) {
    const badges = document.getElementById('symptomBadges');
    const badgeFor = (level) => badges.content.querySelector(`[data-level="${level}"]`).cloneNode(true);
    const render = () => {
      const selected = [...document.querySelectorAll('.symptom-tag[aria-pressed="true"]')];
      summary.innerHTML = '';
      if (selected.length === 0) {
        summary.innerHTML = '<p class="mb-0 text-muted">No symptom selected yet.</p>';
        return;
      }
      const list = document.createElement('ul');
      selected.forEach((tag) => {
        const li = document.createElement('li');
        li.append(badgeFor(tag.dataset.severity));
        const text = document.createElement('span');
        text.innerHTML = '<strong></strong> – ';
        text.querySelector('strong').textContent = tag.dataset.symptom;
        text.append(tag.dataset.description);
        li.append(text);
        list.append(li);
      });
      summary.append(list);
      if (selected.some((tag) => tag.dataset.severity === 'high')) {
        const alert = document.createElement('div');
        alert.className = 'sp-alert sp-alert--danger mt-3';
        alert.setAttribute('role', 'alert');
        alert.textContent = 'One or more selected symptoms can be serious. If they are sudden or intense, call 190 (SAMU).';
        summary.append(alert);
      }
    };
    document.querySelectorAll('.symptom-tag').forEach((tag) => {
      tag.addEventListener('click', () => {
        tag.setAttribute('aria-pressed', String(tag.getAttribute('aria-pressed') !== 'true'));
        render();
      });
    });
  }

  /* ---------- Login / register: client-side validation ---------- */
  const showError = (input, message) => {
    const hint = document.querySelector(`[data-error-for="${input.id}"]`);
    input.classList.toggle('is-invalid', message !== '');
    input.setAttribute('aria-invalid', String(message !== ''));
    if (hint) hint.textContent = message;
    return message === '';
  };
  const emailOk = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (event) => {
      event.preventDefault();
      const email = document.getElementById('loginEmail');
      const password = document.getElementById('loginPassword');
      const ok = [
        showError(email, emailOk(email.value.trim()) ? '' : 'Enter a valid email address.'),
        showError(password, password.value.length >= 8 ? '' : 'The password must contain at least 8 characters.'),
      ].every(Boolean);
      if (ok) toast('Prototype: login will be connected to the database in the next sprint.');
    });
  }

  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', (event) => {
      event.preventDefault();
      const get = (id) => document.getElementById(id);
      const password = get('regPassword').value;
      const ok = [
        showError(get('regFirstName'), get('regFirstName').value.trim().length >= 2 ? '' : 'Enter your first name.'),
        showError(get('regLastName'), get('regLastName').value.trim().length >= 2 ? '' : 'Enter your last name.'),
        showError(get('regEmail'), emailOk(get('regEmail').value.trim()) ? '' : 'Enter a valid email address.'),
        showError(get('regPassword'), password.length >= 8 ? '' : 'At least 8 characters.'),
        showError(get('regConfirm'), get('regConfirm').value === password && password !== '' ? '' : 'Passwords do not match.'),
      ].every(Boolean);
      if (ok) toast('Prototype: account creation will be connected to the database in the next sprint.');
    });
  }
})();
