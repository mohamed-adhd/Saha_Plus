<?php
declare(strict_types=1);

/**
 * Base controller of the shared part.
 * - renderFront(): page inside the Front Office shell (layout_kit.php)
 * - render():      page inside the Back Office shell (layout.php) – workshop pattern
 */
abstract class Controller
{
    /** @var array<string, mixed> */
    protected array $viewData = [];

    protected function renderFront(string $view): void
    {
        extract($this->viewData, EXTR_SKIP);
        $currentPage = $currentPage ?? '';
        $GLOBALS['currentPage'] = $currentPage;
        $breadcrumb = $breadcrumb ?? ['Saha+', $title ?? 'Saha+'];
        $backOfficeUrl = base_url('index.php?page=dashboard');
        $stylesheets = $stylesheets ?? [];
        $scripts = $scripts ?? [];

        ob_start();
        require __DIR__ . '/../Views/pages/front/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout_kit.php';
    }

    protected function render(string $view): void
    {
        extract($this->viewData, EXTR_SKIP);
        $viewFile = __DIR__ . '/../Views/pages/back/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(404);
            echo 'Page not found.';
            return;
        }
        $content = $viewFile;
        require __DIR__ . '/../Views/layout.php';
    }
}
