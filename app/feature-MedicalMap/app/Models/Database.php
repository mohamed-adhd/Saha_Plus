<?php
declare(strict_types=1);

/**
 * Opens ONE PDO connection to MySQL and shares it with every model.
 * Usage in a model:  $pdo = Database::connection();
 */
final class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'], $config['port'], $config['dbname'], $config['charset']
            );
            self::$pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // errors become exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // rows as ['column' => value]
                PDO::ATTR_EMULATE_PREPARES => false,                  // real prepared statements
            ]);
        }
        return self::$pdo;
    }
}
