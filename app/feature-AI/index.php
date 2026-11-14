<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/ui-kit/helpers.php';
require_once __DIR__ . '/app/Controllers/Controller.php';
foreach (['Source', 'KnowledgeEntry', 'TriageRule', 'Message', 'Conversation'] as $model) {
    require_once __DIR__ . '/app/Models/' . $model . '.php';
}
foreach (['Front/AssistantController', 'Back/AssistantLogsController', 'Back/KnowledgeBaseController', 'Back/TriageRulesController', 'Back/AssistantSettingsController'] as $controller) {
    require_once __DIR__ . '/app/Controllers/' . $controller . '.php';
}
$routes = require __DIR__ . '/app/routes.php';
$page = filter_input(INPUT_GET, 'page', FILTER_UNSAFE_RAW) ?: 'assistant';
if (!isset($routes[$page])) {
    http_response_code(404);
    $title = 'Page not found';
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Not found</title><body><main><h1>Page not found</h1><a href="index.php?page=assistant">Open assistant</a></main></body></html>';
    exit;
}
[$class, $method] = $routes[$page];
(new $class())->$method();
