<?php
require_once __DIR__ . '/../ui-kit/helpers.php';
$GLOBALS['currentPage'] = $currentPage;
$stylesheets = array_merge($stylesheets ?? [], ['assets/css/assistant.css']);
$scripts = array_merge($scripts ?? [], ['assets/js/assistant.js']);
$backOfficeUrl = base_url('index.php?page=assistant-logs');

ob_start();
$frontView = $frontView ?? 'assistant';
require __DIR__ . '/../app/Views/pages/front/' . $frontView . '.php';
if (($page ?? '') !== 'assistant') {
    require __DIR__ . '/../app/Views/partials/front/assistant_widget.php';
}
$content = ob_get_clean();
require __DIR__ . '/../ui-kit/app/Views/layout_kit.php';
