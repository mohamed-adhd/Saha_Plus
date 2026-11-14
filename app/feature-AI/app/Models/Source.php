<?php
declare(strict_types=1);

final class Source
{
    public function __construct(public string $label, public string $url = '#') {}

    /** @return list<Source> */
    public static function all(): array
    {
        return [new self('World Health Organization'), new self('Tunisian Ministry of Health'), new self('Mayo Clinic')];
    }
}
