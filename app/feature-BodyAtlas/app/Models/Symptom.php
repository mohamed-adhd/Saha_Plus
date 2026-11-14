<?php
declare(strict_types=1);

final class Symptom
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public string $severity,   // low | medium | high
        public int $organId
    ) {}

    /** Prototype data – will be replaced by a database query (PDO) later. */
    /** @return list<Symptom> */
    public static function all(): array
    {
        $rows = [
            [1, 'Headache', 'Pain or pressure in the head.', 'low'],
            [1, 'Dizziness', 'Feeling light-headed or that the room is spinning.', 'medium'],
            [1, 'Memory problems', 'Forgetting recent events more than usual.', 'medium'],
            [1, 'Sudden confusion', 'Difficulty thinking clearly that appears suddenly.', 'high'],
            [2, 'Chest tightness', 'A feeling of pressure or squeezing in the chest.', 'high'],
            [2, 'Palpitations', 'Feeling your heart beating fast or irregularly.', 'medium'],
            [2, 'Shortness of breath', 'Breathlessness during light effort or at rest.', 'high'],
            [2, 'Swollen ankles', 'Fluid building up in the lower legs.', 'medium'],
            [3, 'Persistent cough', 'A cough lasting more than three weeks.', 'medium'],
            [3, 'Wheezing', 'A whistling sound when breathing.', 'medium'],
            [3, 'Shortness of breath', 'Feeling you cannot get enough air.', 'high'],
            [4, 'Heartburn', 'Burning feeling behind the breastbone after meals.', 'low'],
            [4, 'Bloating', 'A full, tight feeling in the abdomen.', 'low'],
            [4, 'Nausea', 'Feeling like you need to vomit.', 'medium'],
            [5, 'Fatigue', 'Tiredness that does not go away with rest.', 'low'],
            [5, 'Yellow skin', 'Yellowing of the skin or the white of the eyes.', 'high'],
            [5, 'Upper-right pain', 'Pain under the ribs on the right side.', 'medium'],
            [6, 'Frequent urination', 'Needing to urinate more often than usual.', 'low'],
            [6, 'Lower back pain', 'Pain on one side of the lower back.', 'medium'],
            [6, 'Foamy urine', 'Urine with persistent bubbles.', 'medium'],
            [7, 'Blurred vision', 'Difficulty seeing sharp details.', 'medium'],
            [7, 'Red eyes', 'Redness with itching or discharge.', 'low'],
            [7, 'Eye strain', 'Tired, dry eyes after screen use.', 'low'],
            [8, 'Joint pain', 'Pain in a joint during movement or at rest.', 'low'],
            [8, 'Stiffness', 'Difficulty moving a joint, often in the morning.', 'low'],
            [8, 'Muscle cramps', 'Sudden painful tightening of a muscle.', 'low'],
            [8, 'Swollen joint', 'A joint that is swollen, warm or red.', 'medium'],
        ];
        $items = [];
        foreach ($rows as $i => [$organId, $name, $description, $severity]) {
            $items[] = new self($i + 1, $name, $description, $severity, $organId);
        }
        return $items;
    }

    /** @return list<Symptom> */
    public static function byOrgan(int $organId): array
    {
        return array_values(array_filter(self::all(), fn (self $s) => $s->organId === $organId));
    }
}
