<?php
/**
 * Front-office layout: Saha+ shell (ui-kit partials) + spinner, footer and back-to-top.
 *
 * Variables provided by Controller::render():
 *   $title, $currentPage, $content, $stylesheets, $scripts
 */
require_once __DIR__ . '/../../ui-kit/helpers.php';
$backOfficeUrl = base_url('index.php?page=assistant-logs');
?><!doctype html>
<html lang="en">
<head><?php require_once __DIR__ . '/../../ui-kit/app/Views/partials/head.php'; ?></head>
<body class="sp-bg">
  <?php require_once __DIR__ . '/partials/front/spinner.php'; ?>
  <a class="sp-skip-link" href="#spMain">Skip to main content</a>
  <span class="sp-botanical sp-botanical--top" aria-hidden="true"></span>
  <span class="sp-botanical sp-botanical--bottom" aria-hidden="true"></span>
  <div class="sp-app-shell">
    <?php require_once __DIR__ . '/../../ui-kit/app/Views/partials/sidebar.php'; ?>
    <div class="sp-app-frame">
      <?php require_once __DIR__ . '/../../ui-kit/app/Views/partials/topbar.php'; ?>
      <div class="sp-content" id="spMain"><?= $content ?></div>
      <?php if ($currentPage !== 'health-guide') { require_once __DIR__ . '/partials/front/assistant_widget.php'; } ?>
      <?php require_once __DIR__ . '/partials/front/footer.php'; ?>
    </div>
  </div>
  <?php require_once __DIR__ . '/../../ui-kit/app/Views/partials/scripts.php'; ?>
</body>
</html>
