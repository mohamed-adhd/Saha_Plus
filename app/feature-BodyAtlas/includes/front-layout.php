<?php
/*
 * Front Office assembly: captures the page view into $content,
 * then hands it to the shared Saha+ shell (sidebar + topbar + scripts).
 * Expects: $title, $currentPage, $frontView (+ any data the view needs).
 */
require_once __DIR__ . '/../ui-kit/helpers.php';
$GLOBALS['currentPage'] = $currentPage;
$stylesheets = array_merge($stylesheets ?? [], ['assets/css/atlas.css']);
$scripts = array_merge($scripts ?? [], ['assets/js/atlas.js']);
$breadcrumb = $breadcrumb ?? ['Saha+', $title];
$backOfficeUrl = base_url('index.php?page=admin-organs');

ob_start();
require __DIR__ . '/../app/Views/pages/front/' . $frontView . '.php';
$content = ob_get_clean();
require __DIR__ . '/../ui-kit/app/Views/layout_kit.php';
