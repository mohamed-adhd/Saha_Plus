<?php
declare(strict_types=1);
require_once __DIR__ . '/helpers.php';

$title = 'UI Kit Preview';
$currentPage = 'health-map';
$GLOBALS['currentPage'] = $currentPage;
$breadcrumb = ['Saha+', 'UI Kit Preview'];
$backOfficeUrl = base_url('index.php');
$stylesheets = [];
$scripts = [];
ob_start();
?>
<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5">
  <header class="sp-page-header" data-sp-reveal>
    <div class="sp-page-header__copy"><nav class="sp-breadcrumb" aria-label="Breadcrumb"><a href="<?= htmlspecialchars(base_url('index.php'), ENT_QUOTES, 'UTF-8') ?>">Home</a><span class="sp-breadcrumb__separator" aria-hidden="true">/</span><span>UI Kit Preview</span></nav><h1>A thoughtful daily overview</h1><p>Small, steady steps make a healthy routine easier to keep.</p></div>
    <button class="sp-button sp-button--primary" type="button" data-sp-toast="Your overview is up to date.">Show notification</button>
  </header>

  <section class="row g-3 mb-4" aria-label="Example statistics">
    <div class="col-sm-6 col-xl-3" data-sp-reveal><article class="sp-stat-card"><p class="sp-stat-card__label">Daily movement</p><p class="sp-stat-card__value">28 <small>min</small></p><p class="sp-stat-card__note">A good start to the week</p></article></div>
    <div class="col-sm-6 col-xl-3" data-sp-reveal><article class="sp-stat-card"><p class="sp-stat-card__label">Water breaks</p><p class="sp-stat-card__value">5 <small>today</small></p><p class="sp-stat-card__note">Keep a glass nearby</p></article></div>
    <div class="col-sm-6 col-xl-3" data-sp-reveal><article class="sp-stat-card"><p class="sp-stat-card__label">Rest last night</p><p class="sp-stat-card__value">7.5 <small>hours</small></p><p class="sp-stat-card__note">A steady routine can help</p></article></div>
    <div class="col-sm-6 col-xl-3" data-sp-reveal><article class="sp-stat-card"><p class="sp-stat-card__label">Weekly check-in</p><p class="sp-stat-card__value">4 <small>days</small></p><p class="sp-stat-card__note">Consistency over perfection</p></article></div>
  </section>

  <div class="row g-4">
    <div class="col-xl-7">
      <section class="sp-card mb-4" data-sp-reveal><header class="sp-card__header d-flex justify-content-between align-items-center gap-3"><div><span class="sp-pill">Today</span><h2 class="h4 mt-3 mb-0">A few gentle reminders</h2></div><span class="sp-badge sp-badge--info">On track</span></header><div class="sp-card__body"><p>Taking a short walk, adding a fruit or vegetable to lunch, and keeping a regular sleep time are practical ways to care for everyday wellbeing.</p><div class="d-flex flex-wrap gap-2"><button class="sp-button sp-button--soft" type="button">Mark as read</button><button class="sp-button sp-button--outline" type="button" data-sp-modal-open="routineModal">View routine</button></div></div></section>
      <section data-sp-reveal><div class="sp-section-title"><div><h2>Recent activity</h2><p>A simple overview of your week.</p></div><span class="sp-badge sp-badge--warn">Needs review</span></div><div class="sp-table-wrap"><table class="sp-table"><thead><tr><th scope="col">Day</th><th scope="col">Activity</th><th scope="col">Note</th><th scope="col">Status</th></tr></thead><tbody><tr><td>Monday</td><td>Outdoor walk</td><td>28 minutes</td><td><span class="sp-badge sp-badge--info">Complete</span></td></tr><tr><td>Tuesday</td><td>Routine check-in</td><td>Rest and hydration</td><td><span class="sp-badge sp-badge--neutral">Planned</span></td></tr><tr><td>Wednesday</td><td>Community visit</td><td>Local wellness event</td><td><span class="sp-badge sp-badge--danger">Reschedule</span></td></tr></tbody></table></div></section>
    </div>
    <div class="col-xl-5">
      <section class="sp-card mb-4" data-sp-reveal><div class="sp-card__body"><h2 class="h4">Your preferences</h2><div class="sp-field"><label for="demoName">Display name</label><input class="sp-input" id="demoName" value="Samira"><span class="sp-field__hint">This name is shown only in your profile.</span></div><div class="sp-field"><label for="demoFocus">Wellbeing focus</label><select class="sp-select" id="demoFocus"><option>Everyday movement</option><option>Rest and routine</option><option>Balanced meals</option></select></div><div class="sp-field"><label for="demoNote">A note for yourself</label><textarea class="sp-textarea" id="demoNote" placeholder="Write a small goal for this week"></textarea></div><button class="sp-button sp-button--primary" type="button" data-sp-toast="Your preferences have been saved.">Save preferences</button></div></section>
      <section class="sp-alert sp-alert--warn mb-4" role="status" data-sp-reveal><span aria-hidden="true">●</span><div><strong>Take a moment for yourself.</strong><br>Short breaks can be a helpful part of a balanced day.</div></section>
      <section class="sp-empty mb-4" data-sp-reveal><span class="sp-empty__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21V9M12 14C5 15 3 11 4 6c5 0 8 2 8 8Zm0-5c0-5 3-7 8-7 0 5-3 8-8 8Z"/></svg></span><h3>No saved routines yet</h3><p>When you save a routine, it will appear here for easy reference.</p></section>
      <section class="sp-card" data-sp-reveal><div class="sp-card__body"><h2 class="h4">Loading preview</h2><div class="sp-skeleton sp-skeleton--title"></div><div class="sp-skeleton sp-skeleton--text"></div><div class="sp-skeleton sp-skeleton--text sp-skeleton--short"></div></div></section>
    </div>
  </div>

  <section class="sp-card mt-4" data-sp-reveal><div class="sp-card__body d-flex flex-wrap align-items-center justify-content-between gap-3"><div><span class="sp-pill">Profile hint</span><h2 class="h4 mt-2 mb-1">Make this space your own</h2><p class="mb-0">Use clear labels and helpful details to keep each section easy to understand.</p></div><span class="sp-tooltip" data-sp-tooltip="A short note can explain why this information is useful." tabindex="0"><button class="sp-button sp-button--outline" type="button">What is this?</button></span></div></section>
</div>

<dialog class="sp-modal" id="routineModal" aria-labelledby="routineModalTitle"><header class="sp-modal__header"><h2 id="routineModalTitle">A balanced daily routine</h2><button class="sp-button sp-button--soft" type="button" aria-label="Close dialog" data-sp-modal-close>Close</button></header><div class="sp-modal__body"><p>Leave room for rest, regular meals, movement that feels comfortable, and time with people you care about.</p><div class="sp-alert">Small changes can be easier to maintain when they fit your everyday life.</div></div><footer class="sp-modal__footer"><button class="sp-button sp-button--primary" type="button" data-sp-modal-close>Done</button></footer></dialog>
<?php
$content = ob_get_clean();
require __DIR__ . '/app/Views/layout_kit.php';
