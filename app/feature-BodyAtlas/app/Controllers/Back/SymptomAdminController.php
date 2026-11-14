<?php
declare(strict_types=1);

final class SymptomAdminController extends Controller
{
    public function index(): void
    {
        $page = 'back/symptoms'; $title = 'Symptoms'; $currentPage = 'admin-symptoms';
        $symptoms = Symptom::all(); $organs = Organ::all();
        $this->viewData = compact('page', 'title', 'currentPage', 'symptoms', 'organs');
        $this->render($page);
    }
}
