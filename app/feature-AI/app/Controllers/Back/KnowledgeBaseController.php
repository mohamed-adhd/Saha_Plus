<?php
declare(strict_types=1);

final class KnowledgeBaseController extends Controller
{
    public function index(): void
    {
        $page = 'back/knowledge_base'; $title = 'Knowledge Base'; $currentPage = 'knowledge-base';
        $entries = KnowledgeEntry::all(); $sources = Source::all();
        $this->viewData = compact('page', 'title', 'currentPage', 'entries', 'sources');
        $this->render($page);
    }
}
