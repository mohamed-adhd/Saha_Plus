<?php
declare(strict_types=1);

final class AssistantController extends Controller
{
    protected string $layout = 'layout_front';
    protected string $page = 'health-guide';   // matches the 'AI Assistant' key in ui-kit/config/menu.php
    protected string $title = 'AI Health Assistant';
    protected array $stylesheets = ['app/Views/assets/css/assistant.css', 'app/Views/assets/css/shell.css'];
    protected array $scripts = ['app/Views/assets/js/shell.js', 'app/Views/assets/js/assistant.js'];

    public function index(): void
    {
        $this->render('front/assistant', ['sources' => Source::all()]);
    }

    public function comingSoon(): void
    {
        $modules = [
            'home' => 'Home', 'body-atlas' => 'Body Atlas', 'health-map' => 'Health Map',
            'blood-donation' => 'Blood Donation', 'mental-health' => 'Mental Health SafeSpace',
        ];
        $module = filter_input(INPUT_GET, 'module', FILTER_UNSAFE_RAW) ?: 'home';
        if (!isset($modules[$module])) { $module = 'home'; }

        $this->page = $module;            // key used for the active sidebar link
        $this->title = $modules[$module];
        $this->render('front/coming_soon');
    }
}
