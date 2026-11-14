<?php /** @var Module $module */ ?>
<div class="container-fluid px-3 px-lg-5 py-5">
  <section class="sp-empty" data-sp-reveal>
    <span class="sp-empty__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21V9M12 14C5 15 3 11 4 6c5 0 8 2 8 8Zm0-5c0-5 3-7 8-7 0 5-3 8-8 8Z"/></svg></span>
    <h3><?= e($module->name) ?></h3>
    <p><?= e($module->description) ?><br>Developed by <?= e($module->owner) ?>.</p>
    <?php if ($module->isAvailable()): ?>
      <a class="sp-button sp-button--primary" href="<?= e($module->url()) ?>">Open the <?= e($module->name) ?> module</a>
    <?php else: ?>
      <a class="sp-button sp-button--primary" href="<?= e(base_url('index.php')) ?>">This module is coming soon – back home</a>
    <?php endif; ?>
  </section>
</div>
