<?php
declare(strict_types=1);

final class Message
{
    public function __construct(public string $role, public string $text, public string $time) {}

    /** @return list<Message> */
    public static function all(): array
    {
        return [new self('user', 'How can I prevent diabetes?', '09:15'), new self('assistant', 'Balanced eating, regular movement and preventive checkups support lower diabetes risk.', '09:15')];
    }
}
