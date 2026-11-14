<?php
declare(strict_types=1);

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $basePath = dirname($scriptName);
        if (basename($basePath) === 'shared') {
            $basePath = dirname($basePath);
        }
        $basePath = rtrim($basePath, '/.');
        if ($basePath === '/') { $basePath = ''; }
        $path = ltrim($path, '/');
        return $basePath . ($path === '' ? '' : '/' . $path);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return base_url($path);
    }
}

if (!function_exists('navActive')) {
    function navActive(string $key): string
    {
        return (string)($GLOBALS['currentPage'] ?? '') === $key ? 'active' : '';
    }
}

if (!function_exists('e')) {
    /** Escape a value before printing it in HTML. */
    function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
