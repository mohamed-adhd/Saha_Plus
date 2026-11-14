<?php
/** Shared Back Office shell (workshop pattern): sidebar + header + page content + footer. */
?><!doctype html>
<html lang="en"><head><?php require_once __DIR__ . '/partials/back/head.php'; ?></head><body class="sp-bg admin-body">
<?php require_once __DIR__ . '/partials/back/sidebar.php'; ?>
<div class="main-wrapper"><main class="main-content"><?php require_once __DIR__ . '/partials/back/header.php'; ?>
<div class="sp-content"><?php require $content; ?></div>
<?php require_once __DIR__ . '/partials/back/footer.php'; ?></main></div>
<?php require_once __DIR__ . '/partials/back/scripts.php'; ?>
</body></html>
