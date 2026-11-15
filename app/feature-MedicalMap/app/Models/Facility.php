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
