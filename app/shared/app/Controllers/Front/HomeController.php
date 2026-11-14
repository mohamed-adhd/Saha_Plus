<?php
declare(strict_types=1);

final class HomeController extends Controller
{
    public function index(): void
    {
        $title = 'Welcome';
        $currentPage = 'home';
        $modules = Module::all();
        $this->viewData = compact('title', 'currentPage', 'modules');
        $this->renderFront('home');
    }

    /** Menu links point here; the real pages live in each feature folder. */
    public function comingSoon(): void
    {
        $key = (string)(filter_input(INPUT_GET, 'module', FILTER_UNSAFE_RAW) ?: 'home');
        $module = Module::find($key);
        if ($module === null) {
            $this->index();
            return;
        }
        $title = $module->name;
        $currentPage = $module->key;
        $this->viewData = compact('title', 'currentPage', 'module');
        $this->renderFront('coming_soon');
    }
}
