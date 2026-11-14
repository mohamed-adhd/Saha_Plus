<?php
declare(strict_types=1);

final class AssistantSettingsController extends Controller
{
    protected string $page = 'assistant-settings';
    protected string $title = 'Assistant Settings';

    public function index(): void
    {
        $this->render('back/assistant_settings');
    }
}
