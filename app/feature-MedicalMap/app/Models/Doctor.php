<?php
declare(strict_types=1);

/**
 * A doctor (table `doctor`).
 * Relationship: each Doctor belongs to exactly ONE Facility (foreign key facility_id).
 */
final class Doctor
{
    /** Specialties offered in the search box and in the Back Office form. */
    public const SPECIALTIES = [
        'Cardiology', 'Dentistry', 'Dermatology', 'General medicine', 'Gynecology',
        'Neurology', 'Ophthalmology', 'Orthopedics', 'Pediatrics',
    ];

    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $specialty,
        public ?string $phone,
        public ?string $email,
        public int $facilityId
    ) {}

    /** @return list<Doctor> every doctor, sorted by name */
    public static function all(): array
    {
        $rows = Database::connection()
            ->query('SELECT * FROM doctor ORDER BY last_name, first_name')
            ->fetchAll();
        return array_map([self::class, 'fromRow'], $rows);
    }

    /** @return list<Doctor> the doctors of one facility */
    public static function byFacility(int $facilityId): array
    {
        $statement = Database::connection()
            ->prepare('SELECT * FROM doctor WHERE facility_id = :facility_id ORDER BY last_name, first_name');
        $statement->execute(['facility_id' => $facilityId]);
        return array_map([self::class, 'fromRow'], $statement->fetchAll());
    }

    public function fullName(): string
    {
        return 'Dr. ' . $this->firstName . ' ' . $this->lastName;
    }

    /** Converts one database row into a Doctor object. */
    private static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (string)$row['specialty'],
            $row['phone'] !== null ? (string)$row['phone'] : null,
            $row['email'] !== null ? (string)$row['email'] : null,
            (int)$row['facility_id']
        );
    }
}
