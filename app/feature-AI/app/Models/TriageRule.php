<?php
declare(strict_types=1);

final class TriageRule
{
    /** @param list<string> $keywords */
    public function __construct(public string $tier, public string $label, public array $keywords) {}

    /** @return list<TriageRule> */
    public static function all(): array
    {
        return [
            new self('informational', 'Informational', ['prevention', 'healthy habits', 'what is', 'how can']),
            new self('consult', 'Consult a professional', ['persistent', 'fever for days', 'blood in', 'dizziness']),
            new self('urgent', 'Urgent', ['chest pain', "can't breathe", 'stroke', 'severe bleeding', 'unconscious', 'suicidal']),
        ];
    }
}
