<?php
/** Generic Saha+ shell. The supplied content is rendered inside .sp-content. */
?><!doctype html>
<html lang="en">
<head><?php require_once __DIR__ . '/partials/head.php'; ?></head>
<body class="sp-bg">
  <a class="sp-skip-link" href="#spMain">Skip to main content</a>
  <span class="sp-botanical sp-botanical--top" aria-hidden="true"></span>
  <span class="sp-botanical sp-botanical--bottom" aria-hidden="true"></span>
  <div class="sp-app-shell">
    <?php require_once __DIR__ . '/partials/sidebar.php'; ?>
    <div class="sp-app-frame">
      <?php require_once __DIR__ . '/partials/topbar.php'; ?>
      <div class="sp-content" id="spMain"><?= $content ?></div>
    </div>
  </div>
  <?php require_once __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
