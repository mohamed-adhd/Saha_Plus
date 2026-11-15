<?php
declare(strict_types=1);

/**
 * Base controller (workshop pattern, same idea as the other Saha+ modules).
 *
 * render() runs the page view, keeps its HTML in $content, then includes the layout,
 * which assembles head / sidebar / header / content / footer / scripts.
 */
abstract class Controller
{
    /** Page key, used by the sidebars to highlight the active link. */
    protected string $page = 'health-map';

    /** Text of the <title> tag and of the breadcrumb. */
    protected string $title = 'Medical Map';

    /** Layout file in app/Views/: 'layout' = Back Office, 'layout_front' = Front Office. */
    protected string $layout = 'layout';

    /** Extra CSS / JS files of this module (paths relative to the module folder). */
    protected array $stylesheets = [];
    protected array $scripts = [];

    /** Extra CSS / JS loaded from a CDN (full URLs), e.g. Leaflet. Used by the front layout. */
    protected array $externalStylesheets = [];
    protected array $externalScripts = [];

    /**
     * @param string $view view name without .php, relative to app/Views/pages (e.g. 'front/map')
     * @param array  $data variables made available to the view
     */
    protected function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../Views/pages/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(404);
            echo 'Page not found.';
            return;
        }

        $title = $this->title;
        $currentPage = $this->page;
        $stylesheets = $this->stylesheets;
        $scripts = $this->scripts;
        $externalStylesheets = $this->externalStylesheets;
        $externalScripts = $this->externalScripts;
        $GLOBALS['currentPage'] = $currentPage;   // read by navActive() in shared/helpers.php

        // 1) run the page view and capture its HTML
        extract($data, EXTR_SKIP);
        ob_start();
        require $viewFile;
        $content = (string)ob_get_clean();

        // 2) place it inside the layout
        require __DIR__ . '/../Views/' . $this->layout . '.php';
    }
}
