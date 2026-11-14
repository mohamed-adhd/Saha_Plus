<?php
declare(strict_types=1);

/** Showcase of the reusable UI components (cards, tables, forms, alerts, modal…). */
final class ComponentsController extends Controller
{
    public function index(): void
    {
        $title = 'UI Kit Preview';
        $currentPage = '';
        $breadcrumb = ['Saha+', 'UI Kit Preview'];
        $this->viewData = compact('title', 'currentPage', 'breadcrumb');
        $this->renderFront('components');
    }
}
