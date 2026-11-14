<?php
declare(strict_types=1);

final class AssistantController extends Controller
{
    public function index(): void
    {
        $page = 'assistant';
        $title = 'AI Health Assistant';
        $currentPage = 'health-guide';
        $sources = Source::all();
        require __DIR__ . '/../../../includes/front-layout.php';
    }

    public function comingSoon(): void
    {
        $modules = [
            'home' => 'Home', 'body-atlas' => 'Body Atlas', 'health-map' => 'Health Map',
            'blood-donation' => 'Blood Donation', 'mental-health' => 'Mental Health SafeSpace',
        ];
        $module = filter_input(INPUT_GET, 'module', FILTER_UNSAFE_RAW) ?: 'home';
        if (!isset($modules[$module])) { $module = 'home'; }
        $title = $modules[$module];
        $page = 'coming-soon';
        $currentPage = $module;
        $frontView = 'coming_soon';
        require __DIR__ . '/../../../includes/front-layout.php';
    }
}
