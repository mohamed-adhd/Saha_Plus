<?php
declare(strict_types=1);

final class User
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,    // stored hashed (password_hash) once the database is connected
        public string $role,        // patient | therapist | admin
        public ?string $bloodType,  // O-, O+, A-, A+, B-, B+, AB-, AB+ or null
        public string $createdAt
    ) {}

    /** Prototype data – will be replaced by a database query (PDO) later. */
    /** @return list<User> */
    public static function all(): array
    {
        $rows = [
            ['Amira', 'Ben Salah', 'amira.bensalah@mail.tn', 'patient', 'A+'],
            ['Youssef', 'Khelifi', 'youssef.khelifi@mail.tn', 'patient', 'O-'],
            ['Sarra', 'Mansour', 'sarra.mansour@mail.tn', 'therapist', null],
            ['Hedi', 'Rekik', 'hedi.rekik@mail.tn', 'patient', 'B+'],
            ['Nour', 'Ayari', 'nour.ayari@mail.tn', 'patient', 'AB+'],
            ['Admin', 'Saha+', 'admin@sahaplus.tn', 'admin', null],
        ];
        $items = [];
        foreach ($rows as $i => [$first, $last, $email, $role, $blood]) {
            $items[] = new self($i + 1, $first, $last, $email, '', $role, $blood, date('Y-m-d', strtotime('-' . ($i * 9) . ' days')));
        }
        return $items;
    }

    public function fullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
