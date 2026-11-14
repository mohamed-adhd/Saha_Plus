<?php
declare(strict_types=1);

final class BodyAtlasController extends Controller
{
    /** Home of the module: the interactive body silhouette. */
    public function index(): void
    {
        $title = 'Body Atlas';
        $currentPage = 'body-atlas';
        $frontView = 'body_atlas';
        $organs = Organ::all();
        require __DIR__ . '/../../../includes/front-layout.php';
    }

    /** Dedicated organ page: index.php?page=organ&slug=heart */
    public function show(): void
    {
        $slug = (string)(filter_input(INPUT_GET, 'slug', FILTER_UNSAFE_RAW) ?: '');
        $organ = Organ::findBySlug($slug);
        if ($organ === null) {
            http_response_code(404);
            $title = 'Organ not found';
            $currentPage = 'body-atlas';
            $frontView = 'organ_not_found';
            require __DIR__ . '/../../../includes/front-layout.php';
            return;
        }
        $title = $organ->name;
        $currentPage = 'body-atlas';
        $breadcrumb = ['Body Atlas', $organ->name];
        $frontView = 'organ';
        $symptoms = Symptom::byOrgan($organ->id);
        require __DIR__ . '/../../../includes/front-layout.php';
    }

    /** Placeholder for the modules built by the other team members. */
    public function comingSoon(): void
    {
        $modules = [
            'home' => 'Home', 'health-map' => 'Health Map', 'blood-donation' => 'Blood Donation',
            'mental-health' => 'Mental Health SafeSpace', 'health-guide' => 'AI Assistant',
        ];
        $module = (string)(filter_input(INPUT_GET, 'module', FILTER_UNSAFE_RAW) ?: 'home');
        if (!isset($modules[$module])) { $module = 'home'; }
        $title = $modules[$module];
        $currentPage = $module;
        $frontView = 'coming_soon';
        require __DIR__ . '/../../../includes/front-layout.php';
    }
}
