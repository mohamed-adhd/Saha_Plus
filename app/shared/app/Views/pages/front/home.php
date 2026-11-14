<?php /** @var list<Module> $modules */ ?>
<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5">
  <header class="sp-page-header" data-sp-reveal>
    <div class="sp-page-header__copy">
      <h1>Welcome to Saha+</h1>
      <p>One platform to understand your body, find care, express how you feel and get safe health guidance.</p>
    </div>
    <a class="sp-button sp-button--outline" href="<?= e(base_url('index.php?page=components')) ?>">UI kit preview</a>
  </header>

  <div class="sp-section-title" data-sp-reveal><div><h2>Our modules</h2><p>Each module is developed by one team member and reuses this shared template.</p></div></div>
  <div class="row g-4">
    <?php foreach ($modules as $module): ?>
      <div class="col-md-6 col-xl-4" data-sp-reveal>
        <article class="sp-card h-100"><div class="sp-card__body d-flex flex-column h-100">
          <div class="d-flex justify-content-between align-items-start gap-2">
            <span class="sp-pill"><?= e($module->owner) ?></span>
            <span class="sp-badge <?= $module->status === 'ready' ? 'sp-badge--info' : 'sp-badge--neutral' ?>"><?= e($module->statusLabel()) ?></span>
          </div>
          <h3 class="h4 mt-3 mb-2"><?= e($module->name) ?></h3>
          <p class="flex-grow-1"><?= e($module->description) ?></p>
          <?php if ($module->isAvailable()): ?>
            <a class="sp-button sp-button--primary align-self-start" href="<?= e($module->url()) ?>">Open module</a>
          <?php else: ?>
            <span class="sp-button sp-button--soft align-self-start" aria-disabled="true">Coming soon</span>
          <?php endif; ?>
        </div></article>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="sp-alert mt-4" role="note" data-sp-reveal><span aria-hidden="true">●</span><div>Saha+ is an educational project. It does not replace medical professionals.</div></div>
</div>
