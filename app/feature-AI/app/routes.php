<?php
return [
    'assistant' => [AssistantController::class, 'index'],
    'coming-soon' => [AssistantController::class, 'comingSoon'],
    'assistant-logs' => [AssistantLogsController::class, 'index'],
    'knowledge-base' => [KnowledgeBaseController::class, 'index'],
    'triage-rules' => [TriageRulesController::class, 'index'],
    'assistant-settings' => [AssistantSettingsController::class, 'index'],
];
