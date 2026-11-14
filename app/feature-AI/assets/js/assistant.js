(() => {
  'use strict';

  // Keep this curated list aligned with KnowledgeEntry::all() for the admin demo.
  const knowledge = [
    { topic: 'Hypertension basics', terms: ['blood pressure', 'hypertension'], answer: 'High blood pressure may not cause noticeable symptoms. Regular checks and a conversation with a health professional can help you understand your readings.', sources: ['WHO', 'Tunisian Ministry of Health'] },
    { topic: 'Diabetes prevention', terms: ['diabetes prevention', 'prevent diabetes'], answer: 'Regular movement, balanced meals and preventive health visits are common parts of diabetes prevention guidance. A professional can help make advice personal to you.', sources: ['WHO', 'Tunisian Ministry of Health'] },
    { topic: 'Sleep habits', terms: ['sleep', 'insomnia', 'rest'], answer: 'A steady sleep and wake time, a calm wind-down routine and a comfortable sleep space can support healthy rest. Persistent sleep difficulties are worth discussing with a professional.', sources: ['WHO', 'Mayo Clinic'] },
    { topic: 'Hydration', terms: ['hydration', 'drink water', 'water intake', 'dehydration'], answer: 'Hydration needs vary with activity, weather and individual health. Drinking water regularly and noticing thirst can be useful everyday habits.', sources: ['WHO'] },
    { topic: 'Nutrition basics', terms: ['nutrition', 'balanced diet', 'healthy eating', 'healthy food'], answer: 'A varied diet that includes vegetables, fruit, pulses and whole grains can support general wellbeing. Local, accessible foods can be part of a balanced pattern.', sources: ['WHO', 'Tunisian Ministry of Health'] },
    { topic: 'Physical activity', terms: ['physical activity', 'exercise', 'being active', 'movement'], answer: 'Regular movement can support overall health. Choose an activity and pace that feel manageable, and ask a professional for tailored guidance if you have concerns.', sources: ['WHO'] },
    { topic: 'Stress and wellbeing', terms: ['stress', 'relaxation', 'anxious'], answer: 'Taking brief pauses, talking with someone you trust and keeping supportive daily routines may help with everyday stress. If distress persists or feels overwhelming, reach out to a qualified professional.', sources: ['WHO', 'Mayo Clinic'] },
    { topic: 'Blood donation basics', terms: ['blood donation', 'donate blood', 'donor'], answer: 'Blood donation eligibility depends on local guidance and individual health. Contact an official donation center to learn the current requirements before donating.', sources: ['Tunisian Ministry of Health'] },
    { topic: 'Hand hygiene', terms: ['hand hygiene', 'wash my hands', 'washing hands', 'handwashing'], answer: 'Clean your hands with soap and water, especially before eating and after using the toilet. If soap and water are unavailable, follow local public health advice for hand sanitizer.', sources: ['WHO'] },
    { topic: 'Vaccination in general', terms: ['vaccination', 'vaccines', 'vaccine'], answer: 'Vaccines help protect people from certain infections. Recommended vaccines and timing vary, so check the current Tunisian schedule with a health professional or public health service.', sources: ['WHO', 'Tunisian Ministry of Health'] },
    { topic: 'Persistent symptoms', terms: ['persistent', 'fever for days', 'blood in', 'dizziness'], tier: 'consult', answer: 'A fever that persists for several days, blood in bodily fluids or ongoing dizziness should be assessed by a qualified health professional. Please contact a clinician for personal advice.', sources: ['Tunisian Ministry of Health', 'Mayo Clinic'] },
    { topic: 'Chest pain', terms: ['chest pain'], tier: 'urgent', answer: 'New or severe chest pain may need immediate emergency assessment.', sources: ['WHO'] },
  ];
  const urgentTerms = ['chest pain', "can't breathe", 'cannot breathe', 'stroke', 'severe bleeding', 'unconscious', 'suicidal'];
  const consultTerms = ['persistent', 'fever for days', 'blood in', 'dizziness'];
  const disclaimer = 'Saha+ is educational. It does not diagnose or prescribe.';
  const storageKey = 'sahap-assistant-conversation';
  const sourceUrls = { 'WHO': 'https://www.who.int/', 'Tunisian Ministry of Health': 'https://www.santetunisie.rns.tn/', 'Mayo Clinic': 'https://www.mayoclinic.org/' };

  // MOCK responder. TODO: replace this rule-based logic with a fetch() call to a PHP endpoint.
  function getAssistantReply(message) {
    const text = message.toLocaleLowerCase().replace(/[’‘]/g, "'").replace(/\s+/g, ' ').trim();
    if (urgentTerms.some((term) => text.includes(term))) {
      return { kind: 'medical', tier: 'urgent', answer: 'This may be an emergency. Call 190 (SAMU) now for urgent help. I can’t provide home-treatment advice for this situation.', sources: ['Tunisian Ministry of Health'] };
    }
    if (/\b(hello|hi|salam|bonjour)\b/.test(text)) {
      return { kind: 'neutral', answer: 'Hello, I’m Saha+, your general health information guide. I can help with wellbeing and prevention topics, but I can’t diagnose or prescribe.' };
    }
    if (/\b(who are you|what can you do|what are you|tell me about yourself|what is saha\+?)\b/.test(text)) {
      return { kind: 'neutral', answer: 'I’m Saha+, a friendly guide to general health information and prevention. I can share educational information, but I can’t diagnose or prescribe.' };
    }
    if (/\b(thanks|thank you|thx|merci)\b/.test(text)) {
      return { kind: 'neutral', answer: 'You’re welcome. I’m here if you have another general health or wellbeing question.' };
    }
    if (/\b(bye|goodbye|see you|au revoir|good night)\b/.test(text)) {
      return { kind: 'neutral', answer: 'Take good care. You can come back any time for general health information.' };
    }
    if (consultTerms.some((term) => text.includes(term))) {
      const consultEntry = knowledge.find((entry) => entry.tier === 'consult' && entry.terms.some((term) => text.includes(term)));
      return { kind: 'medical', tier: 'consult', answer: consultEntry?.answer || 'Please contact a qualified health professional promptly for personal advice. If you feel in immediate danger, call 190 (SAMU).', sources: consultEntry?.sources || ['Tunisian Ministry of Health', 'Mayo Clinic'] };
    }
    const match = knowledge.find((entry) => entry.terms.some((term) => text.includes(term)));
    if (match) return { kind: 'medical', tier: match.tier || 'informational', answer: match.answer, sources: match.sources };
    return { kind: 'outside', tier: 'outside', answer: 'I don’t have reliable information about that topic yet. I can help with general questions about blood pressure, sleep or hydration.', sources: [], suggestions: ['What is hypertension?', 'How can I build healthier sleep habits?', 'How can I stay hydrated?'] };
  }

  const messages = document.getElementById('assistantMessages');
  if (messages) {
    const form = document.getElementById('assistantForm');
    const input = document.getElementById('assistantInput');
    const typing = document.getElementById('typingIndicator');
    const chat = document.querySelector('.assistant-chat');
    const emptyState = document.getElementById('assistantEmptyState');
    const sendButton = document.getElementById('sendButton');
    const history = [];
    const persist = () => { try { sessionStorage.setItem(storageKey, JSON.stringify(history)); } catch (_) { /* Storage may be unavailable in private contexts. */ } };
    const addText = (parent, tag, text, className = '') => {
      const node = document.createElement(tag);
      if (className) node.className = className;
      node.textContent = text;
      parent.appendChild(node);
      return node;
    };
    const appendUser = (text, save = true) => {
      const article = document.createElement('article'); article.className = 'message message--user'; article.dataset.history = 'true'; article.dataset.role = 'user';
      const bubble = addText(article, 'div', text, 'message__bubble'); bubble.setAttribute('dir', 'auto');
      addText(article, 'time', new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), 'message__time');
      messages.appendChild(article); messages.scrollTop = messages.scrollHeight;
      if (save) { history.push({ role: 'user', text }); persist(); }
    };
    const makeSvg = (pathData) => {
      const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svg.setAttribute('viewBox', '0 0 24 24'); svg.setAttribute('aria-hidden', 'true');
      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path'); path.setAttribute('d', pathData); svg.append(path); return svg;
    };
    const appendAssistant = (reply, save = true) => {
      const article = document.createElement('article'); article.className = 'message message--assistant'; article.dataset.history = 'true'; article.dataset.role = 'assistant';
      if (reply.kind === 'neutral') {
        const bubble = addText(article, 'div', reply.answer, 'message__bubble assistant-neutral');
        addText(bubble, 'p', disclaimer, 'answer-disclaimer');
      } else {
        const card = document.createElement('div');
        card.className = `answer-card${reply.tier === 'informational' ? '' : ` answer-card--${reply.tier}`}`;
        const label = reply.tier === 'consult' ? 'Consult a professional' : reply.tier === 'urgent' ? 'Urgent' : reply.tier === 'outside' ? 'Outside my scope' : 'Informational';
        const badge = addText(card, 'span', label, `triage-badge triage-badge--${reply.tier}`);
        badge.prepend(makeSvg(reply.tier === 'urgent' ? 'M12 3 3 20h18L12 3Zm0 6v5m0 3h.01' : reply.tier === 'outside' ? 'M12 8h.01M11 12h1v4m-9 2h18V6H3v12Z' : 'm5 12 4 4L19 6'));
        addText(card, 'p', reply.answer, 'answer-card__text');
        if (reply.tier === 'urgent') {
          const alert = document.createElement('div'); alert.className = 'urgent-alert';
          const copy = document.createElement('span'); copy.className = 'urgent-alert__copy';
          const alertIcon = document.createElement('span'); alertIcon.className = 'urgent-alert__icon'; alertIcon.append(makeSvg('M12 3v18M3 12h18'));
          addText(copy, 'span', 'Call 190 (SAMU) now. Do not wait for a chat reply.'); copy.prepend(alertIcon); alert.append(copy);
          const call = document.createElement('a'); call.className = 'urgent-call'; call.href = 'tel:190'; call.append(makeSvg('M7 4h3l2 5-2 2a14 14 0 0 0 3 3l2-2 5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 5 6a2 2 0 0 1 2-2Z')); addText(call, 'span', 'Call 190'); alert.append(call); card.append(alert);
        }
        if (reply.kind === 'outside' && Array.isArray(reply.suggestions)) {
          const suggestions = document.createElement('div'); suggestions.className = 'outside-examples'; suggestions.setAttribute('aria-label', 'Example questions');
          reply.suggestions.forEach((question) => { const button = addText(suggestions, 'button', question, 'question-chip'); button.type = 'button'; button.dataset.question = question; });
          card.append(suggestions);
        }
        if (Array.isArray(reply.sources) && reply.sources.length) {
          const details = document.createElement('details'); details.className = 'answer-sources';
          const summary = addText(details, 'summary', 'Sources'); details.append(summary);
          const chips = document.createElement('div'); chips.className = 'source-chip-list';
          reply.sources.forEach((name) => {
            const chip = document.createElement('a'); chip.className = 'source-chip'; chip.href = sourceUrls[name] || sourceUrls.WHO; chip.target = '_blank'; chip.rel = 'noopener noreferrer';
            chip.append(makeSvg('M14 3h7v7m0-7-9 9M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6'));
            addText(chip, 'span', name); chips.append(chip);
          });
          details.append(chips); card.append(details);
        }
        addText(card, 'p', disclaimer, 'answer-disclaimer');
        article.append(card);
      }
      addText(article, 'time', new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), 'message__time');
      messages.appendChild(article); messages.scrollTop = messages.scrollHeight;
      if (save) { history.push({ role: 'assistant', reply }); persist(); }
    };
    try {
      const stored = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
      if (Array.isArray(stored)) stored.forEach((item) => {
        if (item && item.role === 'user' && typeof item.text === 'string') { history.push(item); appendUser(item.text, false); }
        else if (item && item.role === 'assistant' && item.reply && ['neutral', 'outside', 'medical'].includes(item.reply.kind) && typeof item.reply.answer === 'string') { history.push(item); appendAssistant(item.reply, false); }
      });
    } catch (_) { try { sessionStorage.removeItem(storageKey); } catch (ignore) { /* Storage unavailable. */ } }
    if (history.length) { chat.classList.add('has-started'); emptyState.classList.add('is-hidden'); }
    const send = (raw) => {
      const text = raw.trim(); if (!text) return;
      chat.classList.add('has-started'); emptyState.classList.add('is-hidden'); appendUser(text); input.value = ''; input.style.height = 'auto'; sendButton.disabled = true; typing.classList.remove('d-none');
      window.setTimeout(() => { appendAssistant(getAssistantReply(text)); typing.classList.add('d-none'); }, 450);
    };
    form.addEventListener('submit', (event) => { event.preventDefault(); send(input.value); });
    input.addEventListener('keydown', (event) => { if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); send(input.value); } });
    input.addEventListener('input', () => { input.style.height = 'auto'; input.style.height = `${Math.min(input.scrollHeight, 132)}px`; sendButton.disabled = !input.value.trim(); });
    messages.addEventListener('click', (event) => { const button = event.target.closest('[data-question]'); if (button) send(button.dataset.question); });
    document.querySelectorAll('[data-question]').forEach((button) => button.addEventListener('click', () => send(button.dataset.question)));
    document.getElementById('clearConversation').addEventListener('click', () => { messages.replaceChildren(emptyState); history.length = 0; chat.classList.remove('has-started'); emptyState.classList.remove('is-hidden'); try { sessionStorage.removeItem(storageKey); } catch (_) { /* Storage unavailable. */ } input.focus(); });
  }

  const sidebar = document.getElementById('frontSidebar');
  if (sidebar) {
    const pin = document.getElementById('sidebarPin');
    const openButton = document.getElementById('sidebarOpen');
    const closeButton = document.getElementById('sidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');
    const nav = sidebar.querySelector('.front-nav');
    let touchStartX = null;
    const readPinned = () => { try { return localStorage.getItem('sahap-sidebar-pinned') === 'true'; } catch (_) { return false; } };
    const pinned = readPinned();
    sidebar.classList.toggle('is-pinned', pinned);
    pin.setAttribute('aria-pressed', String(pinned));
    const syncSidebarViewport = () => {
      const mobile = window.innerWidth < 768;
      if (mobile && !sidebar.classList.contains('is-open')) { sidebar.inert = true; sidebar.setAttribute('aria-hidden', 'true'); }
      else { sidebar.inert = false; sidebar.removeAttribute('aria-hidden'); }
      if (!mobile && sidebar.classList.contains('is-open')) {
        sidebar.classList.remove('is-open'); backdrop.classList.remove('is-visible'); backdrop.hidden = true;
        openButton.setAttribute('aria-expanded', 'false');
      }
    };
    const updateIndicator = () => {
      const active = nav.querySelector('.front-nav__item.is-active');
      const indicator = nav.querySelector('.front-nav__indicator');
      if (!active || !indicator) return;
      indicator.style.transform = `translateY(${active.offsetTop}px)`;
      indicator.style.height = `${active.offsetHeight}px`;
      nav.classList.add('has-active');
    };
    const closeDrawer = () => {
      sidebar.classList.remove('is-open'); backdrop.classList.remove('is-visible'); backdrop.hidden = true;
      openButton.setAttribute('aria-expanded', 'false');
      if (window.innerWidth < 768) { sidebar.inert = true; sidebar.setAttribute('aria-hidden', 'true'); }
      if (window.innerWidth < 768) openButton.focus();
    };
    const openDrawer = () => {
      sidebar.inert = false; sidebar.removeAttribute('aria-hidden'); sidebar.classList.add('is-open'); backdrop.hidden = false;
      requestAnimationFrame(() => backdrop.classList.add('is-visible'));
      openButton.setAttribute('aria-expanded', 'true');
      closeButton.focus();
    };
    pin.addEventListener('click', () => {
      const next = !sidebar.classList.contains('is-pinned');
      sidebar.classList.toggle('is-pinned', next); pin.setAttribute('aria-pressed', String(next));
      try { localStorage.setItem('sahap-sidebar-pinned', String(next)); } catch (_) { /* Pin state remains available for this page. */ }
    });
    openButton.addEventListener('click', openDrawer);
    closeButton.addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && sidebar.classList.contains('is-open')) closeDrawer(); });
    sidebar.addEventListener('touchstart', (event) => { touchStartX = event.changedTouches[0].clientX; }, { passive: true });
    sidebar.addEventListener('touchend', (event) => {
      if (touchStartX !== null && touchStartX - event.changedTouches[0].clientX > 55) closeDrawer();
      touchStartX = null;
    }, { passive: true });
    sidebar.querySelectorAll('.front-nav__item').forEach((item) => item.addEventListener('mouseenter', () => {
      if (item.classList.contains('is-active')) updateIndicator();
    }));
    window.addEventListener('resize', () => { syncSidebarViewport(); updateIndicator(); });
    syncSidebarViewport();
    requestAnimationFrame(updateIndicator);
  }

  const infoCards = document.querySelectorAll('.assistant-aside details.info-card');
  const syncInfoAccordion = () => infoCards.forEach((card) => { card.open = window.innerWidth >= 992; });
  if (infoCards.length) { syncInfoAccordion(); window.addEventListener('resize', syncInfoAccordion); }

  const search = document.getElementById('logSearch');
  if (search) {
    const filter = () => document.querySelectorAll('#logsTableBody tr').forEach((row) => {
      const matchesText = row.textContent.toLocaleLowerCase().includes(search.value.toLocaleLowerCase());
      const matchesTier = !document.getElementById('tierFilter').value || row.dataset.tier === document.getElementById('tierFilter').value;
      row.hidden = !(matchesText && matchesTier);
    });
    search.addEventListener('input', filter); document.getElementById('tierFilter').addEventListener('change', filter);
  }

  const entryModal = document.getElementById('entryModal');
  if (entryModal) {
    const modal = bootstrap.Modal.getOrCreateInstance(entryModal); const deleteModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteEntryModal'));
    const form = document.getElementById('entryForm'); const tableBody = document.querySelector('.content-area tbody'); let deleteRow = null;
    const tierLabel = (tier) => tier === 'consult' ? 'Consult a professional' : tier.charAt(0).toUpperCase() + tier.slice(1);
    const newActionButton = (label, action, className) => { const button = document.createElement('button'); button.type = 'button'; button.className = className; button.dataset[action] = 'true'; button.textContent = label; return button; };
    const wireRow = (row, id, topic, summary, source, tier) => {
      row.dataset.entryId = id; row.replaceChildren();
      [topic, summary, source].forEach((value) => { const cell = document.createElement('td'); cell.textContent = value; row.appendChild(cell); });
      const tierCell = document.createElement('td'); const badge = document.createElement('span'); badge.className = `triage-badge triage-badge--${tier}`; badge.textContent = tierLabel(tier); tierCell.appendChild(badge); row.appendChild(tierCell);
      const dateCell = document.createElement('td'); dateCell.textContent = new Date().toISOString().slice(0, 10); row.appendChild(dateCell);
      const actions = document.createElement('td'); actions.className = 'text-nowrap';
      const edit = newActionButton('Edit', 'editEntry', 'btn btn-sm btn-outline-primary'); edit.dataset.topic = topic; edit.dataset.summary = summary; edit.dataset.source = source; edit.dataset.tier = tier;
      const remove = newActionButton('Delete', 'deleteEntry', 'btn btn-sm btn-outline-danger ms-1'); actions.append(edit, remove); row.appendChild(actions);
    };
    document.querySelector('[data-bs-target="#entryModal"]').addEventListener('click', () => { form.reset(); form.classList.remove('was-validated'); document.getElementById('entryId').value = ''; document.getElementById('entryModalTitle').textContent = 'Add knowledge entry'; });
    tableBody.addEventListener('click', (event) => {
      const edit = event.target.closest('[data-edit-entry]'); const remove = event.target.closest('[data-delete-entry]');
      if (edit) { document.getElementById('entryModalTitle').textContent = 'Edit knowledge entry'; document.getElementById('entryId').value = edit.closest('tr').dataset.entryId || edit.dataset.editEntry; document.getElementById('entryTopic').value = edit.dataset.topic; document.getElementById('entrySummary').value = edit.dataset.summary; document.getElementById('entrySource').value = edit.dataset.source; document.getElementById('entryTier').value = edit.dataset.tier; form.classList.remove('was-validated'); modal.show(); }
      if (remove) { deleteRow = remove.closest('tr'); deleteModal.show(); }
    });
    document.getElementById('confirmDeleteEntry').addEventListener('click', () => { if (deleteRow) deleteRow.remove(); deleteRow = null; deleteModal.hide(); });
    form.addEventListener('submit', (event) => {
      event.preventDefault(); event.stopPropagation(); form.classList.add('was-validated'); if (!form.checkValidity()) return;
      const id = document.getElementById('entryId').value || String(Date.now());
      let row = document.getElementById('entryId').value ? tableBody.querySelector(`tr[data-entry-id="${CSS.escape(document.getElementById('entryId').value)}"]`) : null;
      if (!row && document.getElementById('entryId').value) row = [...tableBody.rows].find((item) => item.querySelector(`[data-edit-entry="${CSS.escape(id)}"]`));
      if (!row) { row = document.createElement('tr'); tableBody.prepend(row); }
      wireRow(row, id, document.getElementById('entryTopic').value.trim(), document.getElementById('entrySummary').value.trim(), document.getElementById('entrySource').value, document.getElementById('entryTier').value);
      modal.hide();
    });
  }

  document.querySelectorAll('.keyword-form').forEach((form) => form.addEventListener('submit', (event) => {
    event.preventDefault(); const input = form.querySelector('input'); const value = input.value.trim(); if (!value) return;
    const list = form.parentElement.querySelector('.keyword-chips');
    if ([...list.querySelectorAll('span:first-child')].some((item) => item.textContent.toLocaleLowerCase() === value.toLocaleLowerCase())) { input.setCustomValidity('Keyword already exists'); input.reportValidity(); return; }
    input.setCustomValidity(''); const li = document.createElement('li'); const button = document.createElement('button'); button.type = 'button'; button.className = 'keyword-chip'; button.setAttribute('aria-label', `Remove keyword ${value}`);
    const label = document.createElement('span'); label.textContent = value; const remove = document.createElement('span'); remove.textContent = ' ×'; remove.setAttribute('aria-hidden', 'true'); button.append(label, remove); li.append(button); list.append(li); input.value = '';
  }));
  document.querySelectorAll('.keyword-chips').forEach((list) => list.addEventListener('click', (event) => { const button = event.target.closest('button.keyword-chip'); if (button) button.closest('li').remove(); }));

  const settingsForm = document.getElementById('settingsForm');
  if (settingsForm) settingsForm.addEventListener('submit', (event) => { event.preventDefault(); event.stopPropagation(); settingsForm.classList.add('was-validated'); if (settingsForm.checkValidity()) document.getElementById('settingsStatus').textContent = 'Settings validated (demo only).'; });
})();
