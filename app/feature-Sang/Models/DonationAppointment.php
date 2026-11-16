<?php
class DonationAppointment {
    const STATUS = ['pending','confirmed','done','cancelled'];
    static function create(array $d): void {
        Database::q('INSERT INTO donation_appointments(user_id,facility_id,bloodType,`date`,is_anonymous,name,contact) VALUES(?,?,?,?,?,?,?)', $d);
    }
    private static function sel(): string { return 'SELECT a.*,f.name facility FROM donation_appointments a JOIN facilities f ON f.id=a.facility_id '; }
    static function mine(string $uid): array { return Database::q(self::sel() . 'WHERE a.user_id=? ORDER BY a.`date` DESC', [$uid])->fetchAll(); }
    static function all(): array { return Database::q(self::sel() . 'ORDER BY a.`date` DESC')->fetchAll(); }
    static function setStatus(int $id, string $s): void { Database::q('UPDATE donation_appointments SET status=? WHERE id=?', [$s, $id]); }
    static function cancel(int $id, string $uid): void {
        Database::q("UPDATE donation_appointments SET status='cancelled' WHERE id=? AND user_id=? AND status IN('pending','confirmed')", [$id, $uid]);
    }
}
