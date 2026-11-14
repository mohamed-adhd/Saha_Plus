<?php
declare(strict_types=1);

final class AssistantLogsController extends Controller
{
    protected string $page = 'assistant-logs';
    protected string $title = 'Assistant Logs';

    public function index(): void
    {
        $this->render('back/assistant_logs', ['conversations' => Conversation::all()]);
    }
}
