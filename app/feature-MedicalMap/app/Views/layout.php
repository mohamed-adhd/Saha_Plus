<?php
/**
 * Back Office layout of the Medical Map.
 * It reuses the shared Saha+ admin partials (app/shared) so the theme matches the other modules.
 *
 * Variables provided by Controller::render():
 *   $title, $currentPage, $content (HTML of the page), $stylesheets, $scripts
 */
$sharedBack = __DIR__ . '/../../../shared/app/Views/partials/back';

// Links of the admin sidebar: [ 'SECTION' => [ page key => label ] ]
$adminMenu = ['MEDICAL MAP' => ['admin-facilities' => 'Facilities', 'admin-doctors' => 'Doctors']];
?><!doctype html>
<html lang="en">
<head><?php require_once $sharedBack . '/head.php'; ?></head>
<body class="sp-bg admin-body">
<?php require_once $sharedBack . '/sidebar.php'; ?>
<div class="main-wrapper"><main class="main-content">
  <?php require_once $sharedBack . '/header.php'; ?>
  <div class="sp-content"><?= $content ?></div>
  <?php require_once $sharedBack . '/footer.php'; ?>
</main></div>
<?php require_once $sharedBack . '/scripts.php'; ?>
</body>
</html>
