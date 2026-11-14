<?php
$menuItems = require __DIR__ . '/../../../config/menu.php';
$iconPaths = [
    'home' => '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z"/>',
    'atlas' => '<circle cx="12" cy="5" r="2"/><path d="M9 9h6l2 4-2 2v6h-3v-5h-1v5H8v-6l-1-3 2-3Zm-3 3-2 4m13-4 2 4"/>',
    'map' => '<path d="M3 6 9 3l6 3 6-3v15l-6 3-6-3-6 3V6Z"/><path d="M9 3v15m6-12v15"/><circle cx="12" cy="10" r="2"/>',
    'drop' => '<path d="M12 3s6 7 6 12a6 6 0 0 1-12 0c0-5 6-12 6-12Z"/><path d="M9 15a3 3 0 0 0 3 3m0-8v4m-2-2h4"/>',
    'mind' => '<path d="M12 4a4 4 0 0 0-7 3 4 4 0 0 0 0 7 4 4 0 0 0 7 3V4Zm0 0a4 4 0 0 1 7 3 4 4 0 0 1 0 7 4 4 0 0 1-7 3V4Z"/><path d="M8 9h.01M16 9h.01M9 14c2 2 4 2 6 0"/>',
    'leaf' => '<path d="M12 21V9"/><path d="M12 14C5 15 3 11 4 6c5 0 8 2 8 8Zm0-5c0-5 3-7 8-7 0 5-3 8-8 8Z"/><path d="M8 20c2-1 6-1 8 0"/>',
];
$lastSection = '';
?>
<div class="sp-sidebar-backdrop" id="spSidebarBackdrop" hidden></div>
<aside class="sp-sidebar" id="spSidebar" aria-label="Saha+ navigation">
  <div class="sp-sidebar__brand">
    <a href="<?= htmlspecialchars(base_url('index.php'), ENT_QUOTES, 'UTF-8') ?>" aria-label="Saha+ home">
      <span class="sp-brand-mark"><img src="<?= htmlspecialchars(asset('shared/assets/img/saha-mark.svg'), ENT_QUOTES, 'UTF-8') ?>" alt=""></span>
      <span class="sp-brand-word">Saha<span>+</span></span>
    </a>
    <button type="button" class="sp-sidebar__close" id="spSidebarClose" aria-label="Close navigation"><svg viewBox="0 0 24 24"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
  </div>
  <nav class="sp-sidebar__nav" aria-label="Primary navigation">
    <span class="sp-sidebar__indicator" aria-hidden="true"></span>
    <?php foreach ($menuItems as $item): ?>
      <?php if ($item['section'] !== $lastSection): $lastSection = $item['section']; ?><p class="sp-sidebar__section"><?= htmlspecialchars($lastSection, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
      <?php $active = navActive($item['key']) !== ''; ?>
      <a class="sp-nav-item<?= $active ? ' is-active' : '' ?>" href="<?= htmlspecialchars(base_url($item['url']), ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>" data-nav-key="<?= htmlspecialchars($item['key'], ENT_QUOTES, 'UTF-8') ?>" <?= $active ? 'aria-current="page"' : '' ?>>
        <span class="sp-nav-item__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><?= $iconPaths[$item['icon']] ?? $iconPaths['home'] ?></svg></span>
        <span class="sp-nav-item__label"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
  <div class="sp-sidebar__footer">
    <a class="sp-emergency" href="tel:190" title="Call SAMU emergency services at 190"><span class="sp-emergency__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg></span><span class="sp-nav-item__label"><strong>Emergency 190</strong><small>SAMU · Tunisia</small></span></a>
    <a class="sp-sidebar__footer-link" href="<?= htmlspecialchars($backOfficeUrl ?? base_url('index.php'), ENT_QUOTES, 'UTF-8') ?>" title="Back Office" aria-label="Back Office"><span class="sp-sidebar__footer-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 8h8M8 12h8M8 16h4"/></svg></span><span class="sp-nav-item__label">Back Office</span></a>
    <button class="sp-sidebar__pin" type="button" id="spSidebarPin" aria-label="Keep navigation expanded" aria-pressed="false" title="Keep navigation expanded"><svg viewBox="0 0 24 24"><path d="m16 4 4 4-4 1-3 3 3 3-1 1-3-3-4 7-1-1 5-8-3-3 1-1 3 3 3-3 1-6Z"/></svg><span class="sp-nav-item__label">Keep open</span></button>
  </div>
</aside>
