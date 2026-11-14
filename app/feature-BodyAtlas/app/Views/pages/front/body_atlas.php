<?php
/** @var list<Organ> $organs */
// Position of each organ hotspot on the silhouette (SVG viewBox 0 0 300 600).
$hotspots = [
    'brain' => [150, 42], 'eyes' => [150, 70], 'lungs' => [122, 168], 'heart' => [166, 180],
    'liver' => [128, 232], 'stomach' => [172, 238], 'kidneys' => [150, 278], 'joints' => [128, 462],
];
$layers = ['vital' => 'Vital organs', 'muscular' => 'Muscular & Skeletal', 'nervous' => 'Mental & Nervous'];
?>
<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5 atlas" data-layer="vital">
  <header class="sp-page-header" data-sp-reveal>
    <div class="sp-page-header__copy">
      <p class="atlas-eyebrow">Explore · Understand · Act</p>
      <h1>Your body, explained simply</h1>
      <p>Click a part of the body to learn how it works, the symptoms linked to it and when to see a doctor.</p>
    </div>
    <a class="sp-button sp-button--outline" href="<?= e(base_url('index.php?page=login')) ?>">Log in</a>
  </header>

  <div class="atlas-layers" role="tablist" aria-label="Body system layers" data-sp-reveal>
    <?php foreach ($layers as $key => $label): ?>
      <button type="button" class="atlas-layer<?= $key === 'vital' ? ' is-active' : '' ?>" role="tab"
              aria-selected="<?= $key === 'vital' ? 'true' : 'false' ?>" data-layer-btn="<?= e($key) ?>"><?= e($label) ?></button>
    <?php endforeach; ?>
  </div>

  <div class="row g-4 align-items-start">
    <div class="col-lg-5">
      <section class="sp-card atlas-figure" data-sp-reveal aria-label="Interactive body silhouette">
        <svg viewBox="0 0 300 600" class="atlas-svg" role="img" aria-labelledby="atlasSvgTitle">
          <title id="atlasSvgTitle">Human body silhouette with clickable organs</title>
          <g class="atlas-body">
            <ellipse cx="150" cy="56" rx="34" ry="40"/>
            <rect x="136" y="92" width="28" height="24" rx="8"/>
            <path d="M92 122 Q150 104 208 122 L218 205 Q212 262 202 300 L198 332 L102 332 L98 300 Q88 262 82 205 Z"/>
            <path d="M94 124 Q72 132 68 172 L56 292 Q54 312 64 314 L72 302 L90 196 Z"/>
            <path d="M206 124 Q228 132 232 172 L244 292 Q246 312 236 314 L228 302 L210 196 Z"/>
            <path d="M104 328 L148 328 L145 452 L140 562 Q138 578 120 578 L118 562 L112 452 Z"/>
            <path d="M152 328 L196 328 L188 452 L182 562 Q180 578 162 578 L160 562 L155 452 Z"/>
          </g>
          <?php foreach ($organs as $organ): [$x, $y] = $hotspots[$organ->slug] ?? [150, 300]; ?>
            <a href="<?= e(base_url('index.php?page=organ&slug=' . $organ->slug)) ?>" class="atlas-hotspot"
               data-system="<?= e($organ->system) ?>" data-organ="<?= e($organ->slug) ?>" aria-label="<?= e($organ->name) ?>">
              <circle class="atlas-hotspot__pulse" cx="<?= $x ?>" cy="<?= $y ?>" r="14"/>
              <circle class="atlas-hotspot__dot" cx="<?= $x ?>" cy="<?= $y ?>" r="7"/>
            </a>
          <?php endforeach; ?>
        </svg>
        <p class="atlas-hint">Tip: hover a dot to see the organ name, click to open its page.</p>
      </section>
    </div>

    <div class="col-lg-7">
      <div class="sp-section-title" data-sp-reveal>
        <div><h2 id="atlasLayerTitle">Vital organs</h2><p>Select an organ to open its dedicated page.</p></div>
      </div>
      <div class="row g-3">
        <?php foreach ($organs as $organ): ?>
          <div class="col-sm-6 atlas-organ" data-system="<?= e($organ->system) ?>">
            <a class="sp-card atlas-organ-card" data-organ-card="<?= e($organ->slug) ?>"
               href="<?= e(base_url('index.php?page=organ&slug=' . $organ->slug)) ?>">
              <div class="sp-card__body">
                <span class="sp-pill"><?= e($organ->specialty) ?></span>
                <h3 class="h5 mt-3 mb-1"><?= e($organ->name) ?></h3>
                <p class="mb-0"><?= e($organ->function) ?></p>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="sp-alert mt-4" role="note" data-sp-reveal>
        <span aria-hidden="true">●</span>
        <div>The Body Atlas is educational. It does not replace a medical consultation.</div>
      </div>
    </div>
  </div>
</div>
