<?php
declare(strict_types=1);

/** A feature module of Saha+ (one per team member). */
final class Module
{
    /** @param list<string> $entities */
    public function __construct(
        public string $key,
        public string $name,
        public string $owner,
        public string $icon,
        public string $folder,
        public string $description,
        public array $entities,
        public string $status   // ready | in-progress | planned
    ) {}

    /** Prototype data – will come from the database later. */
    /** @return list<Module> */
    public static function all(): array
    {
        return [
            new self('body-atlas', 'Body Atlas', 'Ibrahim Kbaier', 'atlas', 'feature-BodyAtlas',
                'Interactive human body: organ pages, symptoms, prevention and red flags.', ['Organ', 'Symptom', 'User'], 'ready'),
            new self('health-map', 'Health Map', 'Rayen Mediouni', 'map', 'feature-HealthMap',
                'Hospitals, clinics and doctors on an interactive map, filtered by specialty.', ['Facility', 'Doctor'], 'planned'),
            new self('blood-donation', 'Blood Donation', 'Mohamed Rached Lakhel', 'drop', 'feature-BloodDonation',
                'Blood type compatibility, urgent stock needs and donation appointments.', ['BloodStock', 'DonationAppointment'], 'planned'),
            new self('mental-health', 'SafeSpace', 'Marame Azizi', 'mind', 'feature-SafeSpace',
                'Video, blurred or voice recordings sent to a therapist for asynchronous follow-up.', ['Recording', 'TherapistFeedback'], 'planned'),
            new self('health-guide', 'AI Assistant', 'Mohammed Amdouni', 'leaf', 'feature-AI',
                'Guardrailed health assistant with curated sources and 3-tier triage.', ['Conversation', 'Message'], 'ready'),
        ];
    }

    public static function find(string $key): ?self
    {
        foreach (self::all() as $module) {
            if ($module->key === $key) { return $module; }
        }
        return null;
    }

    /** Each feature has its own index.php next to shared/ (app/<folder>/index.php). */
    public function isAvailable(): bool
    {
        return is_file(__DIR__ . '/../../../' . $this->folder . '/index.php');
    }

    public function url(): string
    {
        return base_url('../' . $this->folder . '/index.php');
    }

    public function statusLabel(): string
    {
        return ['ready' => 'Prototype ready', 'in-progress' => 'In progress', 'planned' => 'Planned'][$this->status] ?? $this->status;
    }
}
