<?php
declare(strict_types=1);

final class OrganAdminController extends Controller
{
    public function index(): void
    {
        $page = 'back/organs'; $title = 'Organs'; $currentPage = 'admin-organs';
        $organs = Organ::all();
        $this->viewData = compact('page', 'title', 'currentPage', 'organs');
        $this->render($page);
    }
}
