<?php
declare(strict_types=1);

final class Conversation
{
    /** @param list<Message> $messages */
    public function __construct(public int $id, public string $date, public string $user, public string $status, public string $tier, public array $messages) {}

    /** @return list<Conversation> */
    public static function all(): array
    {
        $rows = [
            ['Amira B.', 'informational', 'Completed', 'How can I prevent diabetes?', 'General prevention includes regular movement, balanced meals and routine health checks.'],
            ['Youssef K.', 'consult', 'Escalated', 'I have had dizziness for days', 'Persistent dizziness should be discussed with a qualified health professional.'],
            ['Anonymous', 'urgent', 'Escalated', 'I have chest pain', 'Call 190 (SAMU) now for emergency help.'],
            ['Sarra M.', 'informational', 'Completed', 'What is a healthy sleep routine?', 'Consistent sleep and wake times can support healthy sleep habits.'],
            ['Hedi R.', 'consult', 'Escalated', 'There is blood in my urine', 'Please contact a qualified health professional promptly for assessment.'],
            ['Anonymous', 'urgent', 'Escalated', "I can't breathe", 'Call 190 (SAMU) now for emergency help.'],
            ['Nour A.', 'informational', 'Completed', 'How often should I be active?', 'Regular movement suited to your ability can support general wellbeing.'],
            ['Meriem S.', 'consult', 'Open', 'Fever for days', 'A fever lasting several days should be assessed by a qualified professional.'],
        ];
        $items = [];
        foreach ($rows as $i => [$user, $tier, $status, $first, $reply]) {
            $items[] = new self($i + 1, date('Y-m-d', strtotime('-' . $i . ' days')), $user, $status, $tier, [
                new Message('user', $first, '09:15'), new Message('assistant', $reply, '09:15'),
            ]);
        }
        return $items;
    }
}
