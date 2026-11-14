<?php
declare(strict_types=1);

/**
 * Base controller (workshop pattern).
 *
 * render() captures the page-specific view into $content, then includes the
 * layout, which assembles head / sidebar / header / content / footer / scripts.
 */
abstract class Controller
{
    /** Page key, used by the sidebar to flag the active link. */
    protected string $page = 'dashboard';

    /** Title displayed in <title>. */
    protected string $title = 'Saha+';

    /** Layout file (in app/Views/) used to assemble the page. */
    protected string $layout = 'layout';

    /** Extra stylesheets (paths relative to the project root), used by the front layout. */
    protected array $stylesheets = [];

    /** Extra scripts (paths relative to the project root), used by the front layout. */
    protected array $scripts = [];

    /**
     * Renders a content view (app/Views/pages/{$view}.php) inside the common layout.
     *
     * @param string      $view        view name without .php, relative to Views/pages
     * @param array       $data        variables extracted into the content view
     * @param string|null $scriptsView optional page-specific JS view (without .php), relative to Views/pages
     */
    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $viewFile = __DIR__ . '/../Views/pages/' . $view . '.php';
        $layoutFile = __DIR__ . '/../Views/' . $this->layout . '.php';
        if (!is_file($viewFile) || !is_file($layoutFile)) {
            http_response_code(404);
            echo 'Page not found.';
            return;
        }

        $shared = ['title' => $this->title, 'currentPage' => $this->page];
        $content = $this->capture($viewFile, $data + $shared);
        $extraScripts = $scriptsView !== null
            ? $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data + $shared)
            : '';

        $title = $this->title;
        $currentPage = $this->page;
        $stylesheets = $this->stylesheets;
        $scripts = $this->scripts;
        $GLOBALS['currentPage'] = $currentPage; // read by navActive() in ui-kit/helpers.php

        require $layoutFile;
    }

    /** Executes a view file and returns its output as a string. */
    private function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
