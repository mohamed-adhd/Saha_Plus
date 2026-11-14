<?php
/**
 * Back-office layout: includes every common block and injects the page content.
 *
 * Variables provided by Controller::render():
 *   $title        string  - <title> text
 *   $currentPage  string  - key of the current page (active sidebar link)
 *   $content      string  - HTML of the page (already captured)
 *   $extraScripts string  - page-specific JS (optional)
 */
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/sidebar.php';
?>
<div class="main-wrapper"><main class="main-content">
<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="sp-content"><?= $content ?></div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
</main></div>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
