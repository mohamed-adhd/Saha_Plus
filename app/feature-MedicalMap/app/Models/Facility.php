<?php
declare(strict_types=1);

/**
 * A healthcare facility located in Tunisia (table `facility`).
 * Relationship: one Facility has 0..* Doctors.
 */
final class Facility
{
    /** FacilityType enumeration: value stored in the database => label shown to users. */
    public const TYPES = [
        'hospital' => 'Hospital',
        'clinic' => 'Clinic',
        'donation_center' => 'Donation center',
    ];

    public function __construct(
        public int $id,
        public string $name,
        public string $type,            // one of the keys of TYPES
        public string $address,
        public string $city,
        public string $governorate,     // one of Governorate::names()
        public float $latitude,
        public float $longitude,
        public ?string $phone,
        public bool $emergencyStatus,   // true = has an emergency service
        public bool $isDemo             // true = fictional sample data, not a real facility
    ) {}

    /** @return list<Facility> every facility, sorted by governorate then name */
    public static function all(): array
    {
        $rows = Database::connection()
            ->query('SELECT * FROM facility ORDER BY governorate, name')
            ->fetchAll();
        return array_map([self::class, 'fromRow'], $rows);
    }

    public static function find(int $id): ?self
    {
        $statement = Database::connection()->prepare('SELECT * FROM facility WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        return $row === false ? null : self::fromRow($row);
    }

    /**
     * Facilities matching the visitor's filters. Every filter is optional and they combine (AND).
     *
     * @param array{specialty?: string, types?: list<string>, governorate?: string, city?: string} $filters
     * @return list<Facility>
     */
    public static function search(array $filters): array
    {
        $sql = 'SELECT DISTINCT f.* FROM facility f';
        $params = [];

        // Specialty: keep only the facilities that have at least one doctor with this specialty.
        $specialty = trim((string)($filters['specialty'] ?? ''));
        if ($specialty !== '') {
            $sql .= ' JOIN doctor d ON d.facility_id = f.id AND d.specialty LIKE :specialty';
            $params['specialty'] = '%' . addcslashes($specialty, '%_\\') . '%';
        }

        // Tunisia only: even if a wrong row existed, it would never be returned.
        $where = ['f.latitude BETWEEN :lat_min AND :lat_max', 'f.longitude BETWEEN :lng_min AND :lng_max'];
        $params += [
            'lat_min' => Governorate::LAT_MIN, 'lat_max' => Governorate::LAT_MAX,
            'lng_min' => Governorate::LNG_MIN, 'lng_max' => Governorate::LNG_MAX,
        ];

        // Facility types: only known types are accepted.
        $types = array_values(array_intersect((array)($filters['types'] ?? []), array_keys(self::TYPES)));
        if ($types !== []) {
            $placeholders = [];
            foreach ($types as $index => $type) {
                $placeholders[] = ':type' . $index;
                $params['type' . $index] = $type;
            }
            $where[] = 'f.type IN (' . implode(', ', $placeholders) . ')';
        }

        $governorate = (string)($filters['governorate'] ?? '');
        if ($governorate !== '') {
            $where[] = 'f.governorate = :governorate';
            $params['governorate'] = $governorate;
        }

        $city = trim((string)($filters['city'] ?? ''));
        if ($city !== '') {
            $where[] = 'f.city = :city';
            $params['city'] = $city;
        }

        $sql .= ' WHERE ' . implode(' AND ', $where) . ' ORDER BY f.governorate, f.name';
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return array_map([self::class, 'fromRow'], $statement->fetchAll());
    }

    /** @return array<string, list<string>> governorate => cities that have at least one facility */
    public static function citiesByGovernorate(): array
    {
        $rows = Database::connection()
            ->query('SELECT DISTINCT governorate, city FROM facility ORDER BY governorate, city')
            ->fetchAll();
        $cities = [];
        foreach ($rows as $row) {
            $cities[(string)$row['governorate']][] = (string)$row['city'];
        }
        return $cities;
    }

    /* =====================================================================
     * Back Office: list, validation, create / update / delete
     * ===================================================================== */

    /**
     * Facilities for the admin table, optionally filtered by a search text and a type.
     * @return list<Facility>
     */
    public static function adminSearch(string $text = '', string $type = ''): array
    {
        $where = [];
        $params = [];
        if ($text !== '') {
            // Same value under three names: real prepared statements do not allow reusing a name.
            $like = '%' . addcslashes($text, '%_\\') . '%';
            $where[] = '(name LIKE :t1 OR city LIKE :t2 OR governorate LIKE :t3)';
            $params += ['t1' => $like, 't2' => $like, 't3' => $like];
        }
        if (isset(self::TYPES[$type])) {
            $where[] = 'type = :type';
            $params['type'] = $type;
        }
        $sql = 'SELECT * FROM facility' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY governorate, name';
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return array_map([self::class, 'fromRow'], $statement->fetchAll());
    }

    /**
     * Cleans and checks the fields of the facility form.
     *
     * @param array<string, mixed> $input usually $_POST
     * @return array{0: array<string, mixed>, 1: array<string, string>} [clean data, errors by field]
     */
    public static function validate(array $input): array
    {
        $data = [
            'name' => trim((string)($input['name'] ?? '')),
            'type' => (string)($input['type'] ?? ''),
            'address' => trim((string)($input['address'] ?? '')),
            'city' => trim((string)($input['city'] ?? '')),
            'governorate' => (string)($input['governorate'] ?? ''),
            'latitude' => str_replace(',', '.', trim((string)($input['latitude'] ?? ''))),
            'longitude' => str_replace(',', '.', trim((string)($input['longitude'] ?? ''))),
            'phone' => trim((string)($input['phone'] ?? '')),
            'emergencyStatus' => !empty($input['emergencyStatus']),
            'isDemo' => !empty($input['isDemo']),
        ];
        $errors = [];

        if (text_length($data['name']) < 2 || text_length($data['name']) > 120) {
            $errors['name'] = 'Enter a name (2 to 120 characters).';
        }
        if (!isset(self::TYPES[$data['type']])) {
            $errors['type'] = 'Choose a facility type.';
        }
        if ($data['address'] === '' || text_length($data['address']) > 200) {
            $errors['address'] = 'Enter an address (200 characters maximum).';
        }
        if ($data['city'] === '' || text_length($data['city']) > 80) {
            $errors['city'] = 'Enter a city (80 characters maximum).';
        }
        // Tunisia only: the governorate must be one of the 24.
        if (!Governorate::exists($data['governorate'])) {
            $errors['governorate'] = 'Choose one of the 24 Tunisian governorates.';
        }
        // Tunisia only: the coordinates must be numbers located inside the country limits.
        if (!is_numeric($data['latitude']) || !is_numeric($data['longitude'])) {
            $errors['location'] = 'Enter the latitude and the longitude as numbers, or click on the map.';
        } elseif (!Governorate::isInsideTunisia((float)$data['latitude'], (float)$data['longitude'])) {
            $errors['location'] = sprintf(
                'This point is outside Tunisia. Latitude must be between %.2f and %.2f, longitude between %.2f and %.2f.',
                Governorate::LAT_MIN, Governorate::LAT_MAX, Governorate::LNG_MIN, Governorate::LNG_MAX
            );
        }
        if ($data['phone'] !== '' && !is_tunisian_phone($data['phone'])) {
            $errors['phone'] = 'Enter a Tunisian number: 8 digits, optionally starting with +216.';
        }

        return [$data, $errors];
    }

    /** Inserts a facility (data must come from validate()). Returns the new id. */
    public static function create(array $data): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO facility (name, type, address, city, governorate, latitude, longitude, phone, emergency_status, is_demo)
             VALUES (:name, :type, :address, :city, :governorate, :latitude, :longitude, :phone, :emergency_status, :is_demo)'
        );
        $statement->execute(self::toRow($data));
        return (int)Database::connection()->lastInsertId();
    }

    /** Updates a facility (data must come from validate()). */
    public static function update(int $id, array $data): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE facility SET name = :name, type = :type, address = :address, city = :city, governorate = :governorate,
                    latitude = :latitude, longitude = :longitude, phone = :phone,
                    emergency_status = :emergency_status, is_demo = :is_demo
             WHERE id = :id'
        );
        $statement->execute(self::toRow($data) + ['id' => $id]);
    }

    /** Deletes a facility. The controller checks first that it has no doctor (the database refuses otherwise). */
    public static function delete(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM facility WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    /** Values of this facility in the shape expected by the form (same keys as validate()). */
    public function toFormData(): array
    {
        return [
            'name' => $this->name, 'type' => $this->type, 'address' => $this->address, 'city' => $this->city,
            'governorate' => $this->governorate, 'latitude' => (string)$this->latitude, 'longitude' => (string)$this->longitude,
            'phone' => (string)$this->phone, 'emergencyStatus' => $this->emergencyStatus, 'isDemo' => $this->isDemo,
        ];
    }

    /** Converts validated data into the database columns. */
    private static function toRow(array $data): array
    {
        return [
            'name' => $data['name'],
            'type' => $data['type'],
            'address' => $data['address'],
            'city' => $data['city'],
            'governorate' => $data['governorate'],
            'latitude' => round((float)$data['latitude'], 6),
            'longitude' => round((float)$data['longitude'], 6),
            'phone' => $data['phone'] !== '' ? $data['phone'] : null,
            'emergency_status' => $data['emergencyStatus'] ? 1 : 0,
            'is_demo' => $data['isDemo'] ? 1 : 0,
        ];
    }

    /** @return list<Doctor> the doctors who work in this facility (may be empty) */
    public function doctors(): array
    {
        return Doctor::byFacility($this->id);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /** Converts one database row (snake_case columns) into a Facility object. */
    private static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['type'],
            (string)$row['address'],
            (string)$row['city'],
            (string)$row['governorate'],
            (float)$row['latitude'],
            (float)$row['longitude'],
            $row['phone'] !== null ? (string)$row['phone'] : null,
            (bool)$row['emergency_status'],
            (bool)$row['is_demo']
        );
    }
}
