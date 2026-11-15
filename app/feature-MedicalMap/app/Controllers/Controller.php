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
        $csrf = $this->csrfToken();               // hidden field of the Back Office forms
        extract($data, EXTR_SKIP);
        ob_start();
        require $viewFile;
        $content = (string)ob_get_clean();

        // 2) place it inside the layout
        $flash = $this->takeFlash();
        require __DIR__ . '/../Views/' . $this->layout . '.php';
    }

    /* ---------- Helpers used by the Back Office controllers ---------- */

    /** Sends the browser to another page of the module and stops the script. */
    protected function redirect(string $page): never
    {
        header('Location: ' . base_url('index.php?page=' . $page));
        exit;
    }

    /** Stores a message shown once on the next page ($type: 'success' or 'danger'). */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /** @return array{type: string, message: string}|null the stored message, removed after reading */
    private function takeFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /** Secret value placed in every form, so that only our own forms can change the data (CSRF protection). */
    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /** Accepts only a POST request coming from one of our forms; otherwise goes back to $page. */
    protected function requirePost(string $page): void
    {
        $token = (string)($_POST['csrf'] ?? '');
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !hash_equals($this->csrfToken(), $token)) {
            $this->flash('danger', 'The form has expired. Please try again.');
            $this->redirect($page);
        }
    }
}
