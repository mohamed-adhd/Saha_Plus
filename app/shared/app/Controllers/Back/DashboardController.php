<?php
declare(strict_types=1);

final class DashboardController extends Controller
{
    public function index(): void
    {
        $page = 'dashboard'; $title = 'Dashboard'; $currentPage = 'dashboard';
        $modules = Module::all();
        $this->viewData = compact('page', 'title', 'currentPage', 'modules');
        $this->render($page);
    }
}
