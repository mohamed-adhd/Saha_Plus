<?php
declare(strict_types=1);

final class AssistantLogsController extends Controller
{
    public function index(): void
    {
        $page = 'back/assistant_logs'; $title = 'Assistant Logs'; $currentPage = 'assistant-logs';
        $conversations = Conversation::all();
        $this->viewData = compact('page', 'title', 'currentPage', 'conversations');
        $this->render($page);
    }
}
