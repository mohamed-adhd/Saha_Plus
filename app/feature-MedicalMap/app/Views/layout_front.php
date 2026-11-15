<?php
/**
 * Front Office layout of the Medical Map: the shared Saha+ shell (sidebar + top bar).
 * It reuses the partials of app/shared so the theme matches the other modules.
 *
 * Variables provided by Controller::render():
 *   $title, $currentPage, $content (HTML of the page), $stylesheets, $scripts
 */
$sharedFront = __DIR__ . '/../../../shared/app/Views/partials';
$breadcrumb = ['Saha+', $title];
$backOfficeUrl = base_url('index.php?page=admin-facilities');   // "Back Office" link of the sidebar
?><!doctype html>
<html lang="en">
<head>
<?php foreach (($externalStylesheets ?? []) as $url): ?>
<link rel="stylesheet" href="<?= e($url) ?>">
<?php endforeach; ?>
<?php require_once $sharedFront . '/head.php'; ?>
</head>
<body class="sp-bg">
  <a class="sp-skip-link" href="#spMain">Skip to main content</a>
  <span class="sp-botanical sp-botanical--top" aria-hidden="true"></span>
  <span class="sp-botanical sp-botanical--bottom" aria-hidden="true"></span>
  <div class="sp-app-shell">
    <?php require_once $sharedFront . '/sidebar.php'; ?>
    <div class="sp-app-frame">
      <?php require_once $sharedFront . '/topbar.php'; ?>
      <div class="sp-content" id="spMain"><?= $content ?></div>
    </div>
  </div>
  <?php foreach (($externalScripts ?? []) as $url): ?>
  <script src="<?= e($url) ?>" defer></script>
  <?php endforeach; ?>
  <?php require_once $sharedFront . '/scripts.php'; ?>
</body>
</html>
