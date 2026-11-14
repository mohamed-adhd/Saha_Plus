<?php
declare(strict_types=1);

final class AssistantSettingsController extends Controller
{
    public function index(): void
    {
        $page = 'back/assistant_settings'; $title = 'Assistant Settings'; $currentPage = 'assistant-settings';
        $this->viewData = compact('page', 'title', 'currentPage');
        $this->render($page);
    }
}
