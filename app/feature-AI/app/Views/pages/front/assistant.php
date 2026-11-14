<main class="assistant-shell" id="main-content">
  <section class="assistant-hero" aria-labelledby="assistant-title" style="--enter-delay: 80ms">
    <div class="assistant-hero__copy">
      <p class="eyebrow"><span class="eyebrow__line"></span> A little care, whenever you need it</p>
      <h1 id="assistant-title">Hello, how can I help you<br class="desktop-break"> stay well today?</h1>
      <p class="assistant-hero__subtitle">A calm place for general health information and thoughtful next steps.</p>
    </div>
    <div class="hero-leaf" aria-hidden="true">
      <svg viewBox="0 0 132 122" fill="none"><path d="M66 112C64 83 64 55 69 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M67 80C39 82 24 68 19 43c23-5 42 4 48 37Z" fill="#b7cfa8" stroke="currentColor" stroke-width="1.5"/><path d="M67 61c2-25 17-41 43-45 3 25-10 41-43 45Z" fill="#e4eddc" stroke="currentColor" stroke-width="1.5"/><path d="M66 80 33 50M69 57l29-30" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M64 110c-11 2-20 5-27 10m30-10c10 1 18 4 25 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    </div>
  </section>

  <div class="assistant-layout">
    <section class="assistant-chat" aria-label="Chat with Saha+">
      <header class="assistant-chat__header">
        <div class="assistant-identity">
          <span class="assistant-avatar" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none"><path d="M24 40V20" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/><path d="M24 26C12 27 8 21 9 12c9-1 15 3 15 14Z" fill="#b7cfa8" stroke="currentColor" stroke-width="1.7"/><path d="M24 20C24 10 30 5 40 6c1 10-5 15-16 14Z" fill="#e4eddc" stroke="currentColor" stroke-width="1.7"/><path d="m14 16 8 8m6-8 7-7" stroke="#1f4a35" stroke-width="1.3" stroke-linecap="round"/><path d="M17 39c4-2 10-2 14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
          <div><h2>Saha+ Care Guide</h2><p><span class="online-dot"></span><span>Here with you</span></p></div>
        </div>
        <button type="button" class="icon-button clear-button" id="clearConversation" title="Clear conversation" aria-label="Clear conversation"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M6 7l1 13h10l1-13M9 7V4h6v3"/></svg></button>
      </header>

      <div class="assistant-messages" id="assistantMessages" role="log" aria-live="polite" aria-relevant="additions text" aria-label="Conversation">
        <div class="assistant-empty" id="assistantEmptyState">
          <div class="empty-illustration" aria-hidden="true"><svg viewBox="0 0 160 130" fill="none"><path d="M30 103c13-15 31-22 51-22s39 7 50 22" stroke="#6f9a5b" stroke-width="2" stroke-linecap="round"/><path d="M49 78c-2-21 7-34 27-41 5 19-4 34-27 41Zm33-7c4-23 18-33 40-32-1 21-14 33-40 32Z" fill="#b7cfa8" stroke="#3f7a4a" stroke-width="1.5"/><path d="M80 91V49m0 24L61 59m19 7 24-16" stroke="#3f7a4a" stroke-width="2" stroke-linecap="round"/><circle cx="80" cy="89" r="31" stroke="#ece4d2" stroke-width="2" stroke-dasharray="3 6"/></svg></div>
          <h3>Start wherever you are.</h3><p>Ask a question, or choose a gentle place to begin.</p>
          <div class="starter-cards" aria-label="Starter questions">
            <button type="button" class="starter-card" data-question="What are the symptoms of high blood pressure?"><span class="starter-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 12h4l2-5 4 10 2-5h6"/></svg></span><span>Understanding blood pressure</span><span class="starter-card__arrow" aria-hidden="true">↗</span></button>
            <button type="button" class="starter-card" data-question="How can I prevent diabetes?"><span class="starter-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z"/><path d="M7 12h3l1-2 2 4 1-2h3"/></svg></span><span>Small steps for prevention</span><span class="starter-card__arrow" aria-hidden="true">↗</span></button>
            <button type="button" class="starter-card" data-question="How can I build a healthier sleep routine?"><span class="starter-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M19.5 15.5A8 8 0 0 1 8.5 4.5 8.5 8.5 0 1 0 19.5 15.5Z"/><path d="m17 4 .6 1.4L19 6l-1.4.6L17 8l-.6-1.4L15 6l1.4-.6L17 4Z"/></svg></span><span>Rest and daily wellbeing</span><span class="starter-card__arrow" aria-hidden="true">↗</span></button>
          </div>
        </div>
      </div>

      <div class="typing-indicator d-none" id="typingIndicator" role="status" aria-label="Assistant is preparing a reply"><span></span><span></span><span></span><span class="visually-hidden">Assistant is typing</span></div>
      <div class="assistant-composer-wrap">
        <div class="suggested-questions" id="suggestedQuestions" aria-label="Suggested questions">
          <button class="question-chip" type="button" data-question="What are the symptoms of high blood pressure?"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12h4l2-5 4 10 2-5h6"/></svg> Blood pressure</button>
          <button class="question-chip" type="button" data-question="How can I prevent diabetes?"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z"/></svg> Diabetes prevention</button>
          <button class="question-chip question-chip--urgent" type="button" data-question="I have chest pain"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12h4l2-5 4 10 2-5h6"/></svg> Chest pain</button>
        </div>
        <form id="assistantForm" class="assistant-form">
          <label for="assistantInput" class="visually-hidden">Your health question</label>
          <div class="composer-bar"><textarea id="assistantInput" rows="1" maxlength="1000" placeholder="Write what’s on your mind…" aria-describedby="assistantDisclaimer composerHint"></textarea><button class="send-button" id="sendButton" type="submit" aria-label="Send message" disabled><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></button></div>
          <div class="composer-meta"><p id="assistantDisclaimer">Saha+ is educational. It does not diagnose or prescribe.</p><p id="composerHint">Enter to send <span>·</span> Shift+Enter for a new line</p></div>
        </form>
      </div>
    </section>

    <aside class="assistant-aside" aria-label="About this assistant">
      <details class="info-card info-card--care" open style="--enter-delay: 180ms"><summary class="info-card__heading"><span class="info-card__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg></span><h2>What I can help with</h2></summary><div class="info-card__body"><p class="info-row"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>General health education</p><p class="info-row"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>Prevention and everyday wellbeing</p><p class="info-row"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>When to seek professional care</p><div class="soft-divider"></div><p class="info-row info-row--muted"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17"/></svg>I can’t diagnose, prescribe or replace a clinician.</p></div></details>
      <details class="info-card info-card--sources" open style="--enter-delay: 260ms"><summary class="info-card__heading"><span class="info-card__mark info-card__mark--clay" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3v18M5 8h14M7 8c0 4 2 6 5 6s5-2 5-6M8 20h8"/></svg></span><h2>Grounded in trusted sources</h2></summary><div class="source-logos" aria-label="Sources used"><?php foreach ($sources as $source): ?><span class="source-logo"><?= htmlspecialchars($source->label, ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; ?></div><p class="info-footnote">Sources are shown with each health answer.</p></details>
      <details class="info-card info-card--triage" open style="--enter-delay: 340ms"><summary class="info-card__heading"><span class="info-card__mark info-card__mark--sage" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3 4.5 6v5.5c0 5 3.2 8 7.5 9.5 4.3-1.5 7.5-4.5 7.5-9.5V6L12 3Z"/><path d="m9 12 2 2 4-4"/></svg></span><h2>A thoughtful guide</h2></summary><ol class="triage-stepper"><li><span class="step-node step-node--info"></span><div><strong>General information</strong><p>Learn and explore everyday wellbeing.</p></div></li><li><span class="step-node step-node--consult"></span><div><strong>Professional support</strong><p>Some concerns deserve a clinician’s care.</p></div></li><li><span class="step-node step-node--urgent"></span><div><strong>Urgent symptoms</strong><p>Call 190 (SAMU) in an emergency.</p></div></li></ol></details>
    </aside>
  </div>
</main>
