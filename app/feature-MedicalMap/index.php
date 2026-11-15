<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * Medical Map – entry point (front controller / router).
 * Every request goes through this file:  index.php?page=<route key>
 * It loads the classes, finds the route in app/routes.php and calls the controller method.
 */

/*
 * URL helpers. The theme lives in app/shared/ (one folder above this module), so:
 *   base_url('index.php?page=map')         -> a page of THIS module
 *   asset('assets/css/map.css')            -> a file of THIS module
 *   asset('shared/assets/css/tokens.css')  -> a file of the shared Saha+ kit (../shared/...)
 * shared/helpers.php skips functions that are already defined, so these versions win.
 */
function base_url(string $path = ''): string
{
    $base = rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'))), '/');
    return $base . '/' . ltrim($path, '/');
}
function asset(string $path): string
{
    $path = ltrim($path, '/');
    return str_starts_with($path, 'shared/') ? base_url('../' . $path) : base_url($path);
}

require_once __DIR__ . '/../shared/helpers.php';   // navActive(), e()
require_once __DIR__ . '/app/Controllers/Controller.php';
foreach (['Database', 'Governorate', 'Facility', 'Doctor'] as $model) {
    require_once __DIR__ . '/app/Models/' . $model . '.php';
}
foreach (['Front/MapController', 'Back/FacilityAdminController', 'Back/DoctorAdminController'] as $controller) {
    require_once __DIR__ . '/app/Controllers/' . $controller . '.php';
}

$routes = require __DIR__ . '/app/routes.php';
$page = filter_input(INPUT_GET, 'page', FILTER_UNSAFE_RAW) ?: 'map';
if (!isset($routes[$page])) {
    http_response_code(404);
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Not found</title><body><main><h1>Page not found</h1><a href="index.php">Open the Medical Map</a></main></body></html>';
    exit;
}

[$class, $method] = $routes[$page];
try {
    (new $class())->$method();
} catch (PDOException $exception) {
    // The database is not reachable or not imported yet: explain how to fix it instead of a raw error.
    http_response_code(500);
    $detail = htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><title>Database error</title><body style="font-family:sans-serif;max-width:640px;margin:3rem auto">'
        . '<h1>Medical Map – database not available</h1>'
        . '<ol><li>Start <strong>MySQL</strong> in the XAMPP control panel.</li>'
        . '<li>Import <code>app/feature-MedicalMap/database/medical_map.sql</code> in phpMyAdmin.</li>'
        . '<li>Check the settings in <code>app/feature-MedicalMap/config/database.php</code>.</li></ol>'
        . '<p><small>Technical detail: ' . $detail . '</small></p></body></html>';
}
