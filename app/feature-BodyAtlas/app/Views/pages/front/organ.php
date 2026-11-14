<?php
/** @var Organ $organ */
/** @var list<Symptom> $symptoms */
$severityBadge = ['low' => 'sp-badge--info', 'medium' => 'sp-badge--warn', 'high' => 'sp-badge--danger'];
?>
<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5 organ-page">
  <header class="sp-page-header" data-sp-reveal>
    <div class="sp-page-header__copy">
      <nav class="sp-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= e(base_url('index.php?page=body-atlas')) ?>">Body Atlas</a>
        <span class="sp-breadcrumb__separator" aria-hidden="true">/</span>
        <span><?= e($organ->name) ?></span>
      </nav>
      <h1><?= e($organ->name) ?></h1>
      <p><?= e($organ->description) ?></p>
    </div>
    <span class="sp-pill"><?= e($organ->systemLabel()) ?></span>
  </header>

  <!-- 1. Overview & key vitals -->
  <section class="mb-5" data-sp-reveal aria-labelledby="overviewTitle">
    <div class="sp-section-title"><div><h2 id="overviewTitle">1. Overview &amp; key vitals</h2><p><?= e($organ->function) ?></p></div></div>
    <div class="row g-3">
      <?php foreach ($organ->vitals as [$label, $value]): ?>
        <div class="col-sm-6 col-xl-4">
          <article class="sp-stat-card">
            <p class="sp-stat-card__label"><?= e($label) ?></p>
            <p class="sp-stat-card__value organ-vital"><?= e($value) ?></p>
            <p class="sp-stat-card__note">Typical healthy range</p>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 2. Interactive symptom explorer -->
  <section class="mb-5" data-sp-reveal aria-labelledby="symptomTitle">
    <div class="sp-section-title"><div><h2 id="symptomTitle">2. What do you feel?</h2><p>Click the symptoms that match what you feel.</p></div></div>
    <div class="sp-card"><div class="sp-card__body">
      <div class="symptom-tags" role="group" aria-label="Symptoms">
        <?php foreach ($symptoms as $symptom): ?>
          <button type="button" class="symptom-tag" aria-pressed="false"
                  data-symptom="<?= e($symptom->name) ?>" data-description="<?= e($symptom->description) ?>" data-severity="<?= e($symptom->severity) ?>">
            <?= e($symptom->name) ?>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="symptom-summary" id="symptomSummary" aria-live="polite">
        <p class="mb-0 text-muted">No symptom selected yet.</p>
      </div>
      <template id="symptomBadges"><?php foreach ($severityBadge as $level => $class): ?><span data-level="<?= e($level) ?>" class="sp-badge <?= e($class) ?>"><?= e(ucfirst($level)) ?></span><?php endforeach; ?></template>
    </div></div>
  </section>

  <!-- 3. Prevention & red flags -->
  <section class="mb-5" data-sp-reveal aria-labelledby="preventionTitle">
    <div class="sp-section-title"><div><h2 id="preventionTitle">3. Prevention &amp; red flags</h2><p>Daily habits on the left, warning signs on the right.</p></div></div>
    <div class="row g-4">
      <div class="col-md-6">
        <article class="sp-card h-100 organ-prevention"><div class="sp-card__body">
          <h3 class="h5">Healthy habits</h3>
          <ul class="organ-list"><?php foreach ($organ->preventionTips as $tip): ?><li><?= e($tip) ?></li><?php endforeach; ?></ul>
        </div></article>
      </div>
      <div class="col-md-6">
        <article class="sp-card h-100 organ-redflags"><div class="sp-card__body">
          <h3 class="h5">Get urgent care if you notice</h3>
          <ul class="organ-list"><?php foreach ($organ->redFlags as $flag): ?><li><?= e($flag) ?></li><?php endforeach; ?></ul>
          <a class="sp-button sp-button--danger mt-2" href="tel:190">Call SAMU · 190</a>
        </div></article>
      </div>
    </div>
  </section>

  <!-- 4. Smart action bridges (to the AI Assistant and Health Map modules) -->
  <section data-sp-reveal aria-labelledby="actionsTitle">
    <div class="sp-section-title"><div><h2 id="actionsTitle">4. Next steps</h2><p>Continue your journey in the other Saha+ modules.</p></div></div>
    <div class="row g-3">
      <div class="col-md-6">
        <a class="sp-card organ-action" href="<?= e(base_url('index.php?page=coming-soon&module=health-guide&organ=' . $organ->slug)) ?>">
          <div class="sp-card__body"><span class="sp-pill">AI Assistant</span><h3 class="h5 mt-3">Ask Saha+ AI about the <?= e(strtolower($organ->name)) ?></h3><p class="mb-0">Opens the assistant with this organ as context.</p></div>
        </a>
      </div>
      <div class="col-md-6">
        <a class="sp-card organ-action" href="<?= e(base_url('index.php?page=coming-soon&module=health-map&specialty=' . urlencode($organ->specialty))) ?>">
          <div class="sp-card__body"><span class="sp-pill">Health Map</span><h3 class="h5 mt-3">Find a specialist near me</h3><p class="mb-0">Shows <?= e($organ->specialty) ?> doctors on the map.</p></div>
        </a>
      </div>
    </div>
  </section>
</div>
