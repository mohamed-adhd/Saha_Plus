<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * Shared part – standalone entry point.
 * Shows the common Saha+ template (Front Office shell + Back Office shell)
 * that every feature module reuses.  URL: shared/index.php?page=<route>
 */
/*
 * Standalone URL helpers. helpers.php assumes the entry point is in the parent app/ folder
 * (feature modules); here the entry point is shared/index.php, so page links stay in shared/
 * while asset('shared/...') still resolves correctly. helpers.php skips functions already defined.
 */
function base_url(string $path = ''): string
{
    $base = rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'))), '/');
    return $base . '/' . ltrim($path, '/');
}
function asset(string $path): string
{
    $path = ltrim($path, '/');
    return base_url(str_starts_with($path, 'shared/') ? substr($path, 7) : $path);
}

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/app/Controllers/Controller.php';
require_once __DIR__ . '/app/Models/Module.php';
foreach (['Front/HomeController', 'Front/ComponentsController', 'Back/DashboardController'] as $controller) {
    require_once __DIR__ . '/app/Controllers/' . $controller . '.php';
}
$routes = require __DIR__ . '/app/routes.php';
$page = filter_input(INPUT_GET, 'page', FILTER_UNSAFE_RAW) ?: 'home';
if (!isset($routes[$page])) {
    http_response_code(404);
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Not found</title><body><main><h1>Page not found</h1><a href="index.php">Back to Saha+</a></main></body></html>';
    exit;
}
[$class, $method] = $routes[$page];
(new $class())->$method();
