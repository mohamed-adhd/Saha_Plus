<?php
declare(strict_types=1);

/**
 * True for a Tunisian phone number: 8 digits, optionally preceded by +216.
 * Accepted examples: "71 234 567", "71234567", "+216 71 234 567".
 */
function is_tunisian_phone(string $phone): bool
{
    return preg_match('/^(\+216[ ]?)?\d{2}[ ]?\d{3}[ ]?\d{3}$/', $phone) === 1;
}

/** Length of a text in characters (accents count as one character). */
function text_length(string $text): int
{
    return mb_strlen($text, 'UTF-8');
}
