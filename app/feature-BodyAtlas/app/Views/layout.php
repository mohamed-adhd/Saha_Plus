<?php
/** Back-office shell: page content is rendered by the selected controller. */
?><!doctype html>
<html lang="en"><head><?php require_once __DIR__ . '/partials/head.php'; ?></head><body class="sp-bg admin-body">
<?php require_once __DIR__ . '/partials/sidebar.php'; ?>
<div class="main-wrapper"><main class="main-content"><?php require_once __DIR__ . '/partials/header.php'; ?>
<div class="sp-content"><?php require $content; ?></div>
<?php require_once __DIR__ . '/partials/footer.php'; ?></main></div>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
</body></html>
