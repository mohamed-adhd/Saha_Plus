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

    public static function find(int $id): ?self
    {
        $statement = Database::connection()->prepare('SELECT * FROM doctor WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        return $row === false ? null : self::fromRow($row);
    }

    public static function countByFacility(int $facilityId): int
    {
        $statement = Database::connection()->prepare('SELECT COUNT(*) FROM doctor WHERE facility_id = :facility_id');
        $statement->execute(['facility_id' => $facilityId]);
        return (int)$statement->fetchColumn();
    }

    /* =====================================================================
     * Back Office: list, validation, create / update / delete
     * ===================================================================== */

    /**
     * Doctors for the admin table, optionally filtered by a search text and a specialty.
     * @return list<Doctor>
     */
    public static function adminSearch(string $text = '', string $specialty = ''): array
    {
        $where = [];
        $params = [];
        if ($text !== '') {
            $like = '%' . addcslashes($text, '%_\\') . '%';
            $where[] = '(first_name LIKE :t1 OR last_name LIKE :t2 OR email LIKE :t3)';
            $params += ['t1' => $like, 't2' => $like, 't3' => $like];
        }
        if (in_array($specialty, self::SPECIALTIES, true)) {
            $where[] = 'specialty = :specialty';
            $params['specialty'] = $specialty;
        }
        $sql = 'SELECT * FROM doctor' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY last_name, first_name';
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return array_map([self::class, 'fromRow'], $statement->fetchAll());
    }

    /**
     * Cleans and checks the fields of the doctor form.
     *
     * @param array<string, mixed> $input usually $_POST
     * @return array{0: array<string, mixed>, 1: array<string, string>} [clean data, errors by field]
     */
    public static function validate(array $input): array
    {
        $data = [
            'firstName' => trim((string)($input['firstName'] ?? '')),
            'lastName' => trim((string)($input['lastName'] ?? '')),
            'specialty' => (string)($input['specialty'] ?? ''),
            'phone' => trim((string)($input['phone'] ?? '')),
            'email' => trim((string)($input['email'] ?? '')),
            'facilityId' => (int)($input['facilityId'] ?? 0),
        ];
        $errors = [];
        $namePattern = "/^[\p{L}\p{N} '.\-]{2,60}$/u";   // letters, digits, spaces, apostrophes, dots, hyphens

        if (preg_match($namePattern, $data['firstName']) !== 1) {
            $errors['firstName'] = 'Enter a first name (2 to 60 letters).';
        }
        if (preg_match($namePattern, $data['lastName']) !== 1) {
            $errors['lastName'] = 'Enter a last name (2 to 60 letters).';
        }
        if (!in_array($data['specialty'], self::SPECIALTIES, true)) {
            $errors['specialty'] = 'Choose a specialty.';
        }
        if ($data['phone'] !== '' && !is_tunisian_phone($data['phone'])) {
            $errors['phone'] = 'Enter a Tunisian number: 8 digits, optionally starting with +216.';
        }
        if ($data['email'] !== '' && (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false || text_length($data['email']) > 120)) {
            $errors['email'] = 'Enter a valid e-mail address.';
        }
        // A doctor belongs to exactly one EXISTING facility.
        if (Facility::find($data['facilityId']) === null) {
            $errors['facilityId'] = 'Choose the facility where this doctor works.';
        }

        return [$data, $errors];
    }

    /** Inserts a doctor (data must come from validate()). Returns the new id. */
    public static function create(array $data): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO doctor (first_name, last_name, specialty, phone, email, facility_id)
             VALUES (:first_name, :last_name, :specialty, :phone, :email, :facility_id)'
        );
        $statement->execute(self::toRow($data));
        return (int)Database::connection()->lastInsertId();
    }

    /** Updates a doctor (data must come from validate()). */
    public static function update(int $id, array $data): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE doctor SET first_name = :first_name, last_name = :last_name, specialty = :specialty,
                    phone = :phone, email = :email, facility_id = :facility_id
             WHERE id = :id'
        );
        $statement->execute(self::toRow($data) + ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM doctor WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    /** Values of this doctor in the shape expected by the form (same keys as validate()). */
    public function toFormData(): array
    {
        return [
            'firstName' => $this->firstName, 'lastName' => $this->lastName, 'specialty' => $this->specialty,
            'phone' => (string)$this->phone, 'email' => (string)$this->email, 'facilityId' => $this->facilityId,
        ];
    }

    /** Converts validated data into the database columns. */
    private static function toRow(array $data): array
    {
        return [
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'specialty' => $data['specialty'],
            'phone' => $data['phone'] !== '' ? $data['phone'] : null,
            'email' => $data['email'] !== '' ? $data['email'] : null,
            'facility_id' => $data['facilityId'],
        ];
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
