<?php
declare(strict_types=1);

final class TriageRulesController extends Controller
{
    protected string $page = 'triage-rules';
    protected string $title = 'Triage Rules';

    public function index(): void
    {
        $this->render('back/triage_rules', ['rules' => TriageRule::all()]);
    }
}
