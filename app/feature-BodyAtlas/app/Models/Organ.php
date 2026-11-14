<?php
declare(strict_types=1);

final class Organ
{
    /**
     * @param list<array{0: string, 1: string}> $vitals  [label, normal range]
     * @param list<string> $preventionTips
     * @param list<string> $redFlags
     */
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
        public string $system,
        public string $description,
        public string $function,
        public array $vitals,
        public array $preventionTips,
        public array $redFlags,
        public string $specialty,
        public string $image = ''
    ) {}

    /** Prototype data – will be replaced by a database query (PDO) later. */
    /** @return list<Organ> */
    public static function all(): array
    {
        return [
            new self(1, 'brain', 'Brain', 'nervous',
                'The control center of the body, made of billions of nerve cells.',
                'Controls thoughts, memory, emotions, movement and the work of every other organ.',
                [['Sleep', '7–9 h / night'], ['Blood pressure', '< 120/80 mmHg']],
                ['Sleep at regular hours', 'Stay physically active', 'Protect your head (helmet, seatbelt)', 'Keep learning new things'],
                ['Sudden weakness or numbness on one side of the face or body', 'Sudden trouble speaking or understanding', 'Worst headache of your life', 'Seizure or loss of consciousness'],
                'Neurology'),
            new self(2, 'heart', 'Heart', 'vital',
                'A muscular pump about the size of a fist, located in the middle of the chest.',
                'Pumps blood through the body, delivering oxygen and nutrients to every cell.',
                [['Resting heart rate', '60–100 BPM'], ['Blood pressure', '< 120/80 mmHg'], ['Cholesterol (LDL)', '< 100 mg/dL']],
                ['Walk at least 30 minutes a day', 'Limit salt and fried food', 'Do not smoke', 'Check your blood pressure regularly'],
                ['Chest pain or pressure lasting more than a few minutes', 'Pain spreading to the arm, jaw or back', 'Sudden shortness of breath', 'Fainting'],
                'Cardiology'),
            new self(3, 'lungs', 'Lungs', 'vital',
                'Two spongy organs in the chest that fill with air at every breath.',
                'Bring oxygen into the blood and remove carbon dioxide.',
                [['Breathing rate', '12–20 / min'], ['Oxygen saturation (SpO2)', '95–100 %']],
                ['Avoid tobacco and second-hand smoke', 'Exercise to strengthen breathing', 'Air your home every day', 'Get vaccinated against flu'],
                ['Severe difficulty breathing', 'Blue lips or fingertips', 'Coughing up blood', 'Chest pain when breathing'],
                'Pulmonology'),
            new self(4, 'stomach', 'Stomach', 'vital',
                'A J-shaped organ in the upper abdomen, part of the digestive system.',
                'Stores and breaks down food using acid and enzymes.',
                [['Digestion time', '2–5 h'], ['Meals', '3 regular meals / day']],
                ['Eat slowly and at regular times', 'Limit spicy food, coffee and alcohol', 'Do not lie down right after eating', 'Drink enough water'],
                ['Vomiting blood or "coffee grounds"', 'Black, tarry stools', 'Sudden severe abdominal pain', 'Unexplained weight loss'],
                'Gastroenterology'),
            new self(5, 'liver', 'Liver', 'vital',
                'The largest internal organ, on the right side of the upper abdomen.',
                'Filters toxins from the blood, produces bile and stores energy.',
                [['ALT (liver enzyme)', '7–56 U/L'], ['Bilirubin', '0.1–1.2 mg/dL']],
                ['Limit alcohol', 'Keep a healthy weight', 'Get vaccinated against hepatitis B', 'Avoid unnecessary medication'],
                ['Yellow skin or eyes (jaundice)', 'Swollen abdomen', 'Dark urine and pale stools', 'Confusion or extreme drowsiness'],
                'Hepatology'),
            new self(6, 'kidneys', 'Kidneys', 'vital',
                'Two bean-shaped organs in the lower back, on each side of the spine.',
                'Filter the blood, remove waste through urine and balance body fluids.',
                [['Water intake', '1.5–2 L / day'], ['Creatinine', '0.6–1.2 mg/dL']],
                ['Drink water regularly', 'Limit salt', 'Control blood pressure and blood sugar', 'Avoid overusing painkillers'],
                ['Blood in the urine', 'Very little or no urine', 'Severe pain in the side or lower back', 'Swelling of legs and face'],
                'Nephrology'),
            new self(7, 'eyes', 'Eyes', 'nervous',
                'Sensory organs that capture light and send images to the brain.',
                'Allow vision: focus, colors, depth and movement.',
                [['Visual acuity', '10/10'], ['Eye pressure', '10–21 mmHg']],
                ['Take breaks from screens (20-20-20 rule)', 'Wear sunglasses in strong sun', 'Have an eye exam every 1–2 years', 'Eat fruits and vegetables'],
                ['Sudden loss of vision', 'Flashes of light or a curtain over vision', 'Severe eye pain with redness', 'Chemical splash in the eye'],
                'Ophthalmology'),
            new self(8, 'joints', 'Joints & Muscles', 'muscular',
                'The musculoskeletal system: bones, joints and the muscles that move them.',
                'Supports the body, protects organs and allows every movement.',
                [['Physical activity', '150 min / week'], ['Vitamin D', '30–100 ng/mL']],
                ['Warm up before sport', 'Keep a good posture at your desk', 'Lift heavy objects with your legs', 'Eat calcium-rich food'],
                ['Joint that looks deformed after a fall', 'Unable to put weight on a leg', 'Hot, red and swollen joint with fever', 'Numbness after a back injury'],
                'Orthopedics'),
        ];
    }

    public static function findBySlug(string $slug): ?self
    {
        foreach (self::all() as $organ) {
            if ($organ->slug === $slug) {
                return $organ;
            }
        }
        return null;
    }

    public static function findById(int $id): ?self
    {
        foreach (self::all() as $organ) {
            if ($organ->id === $id) {
                return $organ;
            }
        }
        return null;
    }

    public function systemLabel(): string
    {
        return ['vital' => 'Vital organs', 'muscular' => 'Muscular & Skeletal', 'nervous' => 'Mental & Nervous'][$this->system] ?? $this->system;
    }
}
