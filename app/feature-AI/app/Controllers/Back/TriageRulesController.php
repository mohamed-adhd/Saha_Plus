<?php
declare(strict_types=1);

final class TriageRulesController extends Controller
{
    public function index(): void
    {
        $page = 'back/triage_rules'; $title = 'Triage Rules'; $currentPage = 'triage-rules';
        $rules = TriageRule::all();
        $this->viewData = compact('page', 'title', 'currentPage', 'rules');
        $this->render($page);
    }
}
