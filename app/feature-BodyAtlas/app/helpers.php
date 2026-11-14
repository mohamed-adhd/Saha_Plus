<?php
declare(strict_types=1);

/** Escape a value before printing it in HTML (short for htmlspecialchars). */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
