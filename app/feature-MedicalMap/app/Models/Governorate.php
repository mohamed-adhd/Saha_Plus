<?php
declare(strict_types=1);

/**
 * The 24 governorates of Tunisia and the geographical limits of the country.
 * This is a fixed reference list (not a database table): it never changes,
 * and it is what enforces the "Tunisia only" rule of the module.
 */
final class Governorate
{
    /** Bounding box of Tunisia (same limits as the CHECK constraint in database/medical_map.sql). */
    public const LAT_MIN = 30.20;
    public const LAT_MAX = 37.60;
    public const LNG_MIN = 7.50;
    public const LNG_MAX = 11.65;

    /** Governorate name => [latitude, longitude] of its capital city (used to centre the map). */
    private const LIST = [
        'Ariana' => [36.8625, 10.1956],
        'Béja' => [36.7256, 9.1817],
        'Ben Arous' => [36.7531, 10.2189],
        'Bizerte' => [37.2744, 9.8739],
        'Gabès' => [33.8815, 10.0982],
        'Gafsa' => [34.4250, 8.7842],
        'Jendouba' => [36.5011, 8.7802],
        'Kairouan' => [35.6781, 10.0963],
        'Kasserine' => [35.1676, 8.8365],
        'Kébili' => [33.7044, 8.9690],
        'Le Kef' => [36.1742, 8.7049],
        'Mahdia' => [35.5047, 11.0622],
        'Manouba' => [36.8101, 10.0956],
        'Médenine' => [33.3549, 10.5055],
        'Monastir' => [35.7643, 10.8113],
        'Nabeul' => [36.4561, 10.7376],
        'Sfax' => [34.7406, 10.7603],
        'Sidi Bouzid' => [35.0382, 9.4849],
        'Siliana' => [36.0849, 9.3708],
        'Sousse' => [35.8256, 10.6369],
        'Tataouine' => [32.9297, 10.4518],
        'Tozeur' => [33.9197, 8.1335],
        'Tunis' => [36.8065, 10.1815],
        'Zaghouan' => [36.4029, 10.1429],
    ];

    /** @return list<string> the 24 names, in alphabetical order */
    public static function names(): array
    {
        return array_keys(self::LIST);
    }

    public static function exists(string $name): bool
    {
        return isset(self::LIST[$name]);
    }

    /** @return array{0: float, 1: float}|null [latitude, longitude] of the capital */
    public static function center(string $name): ?array
    {
        return self::LIST[$name] ?? null;
    }

    /** True when the point is inside the bounding box of Tunisia. */
    public static function isInsideTunisia(float $latitude, float $longitude): bool
    {
        return $latitude >= self::LAT_MIN && $latitude <= self::LAT_MAX
            && $longitude >= self::LNG_MIN && $longitude <= self::LNG_MAX;
    }
}
