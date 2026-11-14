<?php
declare(strict_types=1);

final class KnowledgeBaseController extends Controller
{
    protected string $page = 'knowledge-base';
    protected string $title = 'Knowledge Base';

    public function index(): void
    {
        $this->render('back/knowledge_base', ['entries' => KnowledgeEntry::all(), 'sources' => Source::all()]);
    }
}
