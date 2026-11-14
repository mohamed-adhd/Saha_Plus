<?php
declare(strict_types=1);

abstract class Controller
{
    /** @var array<string, mixed> */
    protected array $viewData = [];

    protected function render(string $view): void
    {
        extract($this->viewData, EXTR_SKIP);
        $viewFile = __DIR__ . '/../Views/pages/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(404);
            echo 'Page not found.';
            return;
        }
        $content = $viewFile;
        require __DIR__ . '/../Views/layout.php';
    }
}
