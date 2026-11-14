<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/ui-kit/helpers.php';
require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/app/Controllers/Controller.php';
foreach (['Organ', 'Symptom', 'User'] as $model) {
    require_once __DIR__ . '/app/Models/' . $model . '.php';
}
foreach (['Front/BodyAtlasController', 'Front/AuthController', 'Back/OrganAdminController', 'Back/SymptomAdminController', 'Back/UserAdminController'] as $controller) {
    require_once __DIR__ . '/app/Controllers/' . $controller . '.php';
}
$routes = require __DIR__ . '/app/routes.php';
$page = filter_input(INPUT_GET, 'page', FILTER_UNSAFE_RAW) ?: 'body-atlas';
if (!isset($routes[$page])) {
    http_response_code(404);
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Not found</title><body><main><h1>Page not found</h1><a href="index.php?page=body-atlas">Open Body Atlas</a></main></body></html>';
    exit;
}
[$class, $method] = $routes[$page];
(new $class())->$method();
