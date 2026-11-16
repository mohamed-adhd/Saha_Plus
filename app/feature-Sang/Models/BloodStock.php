<?php
class BloodStock {
    const TYPES = ['O-','O+','A-','A+','B-','B+','AB-','AB+'];
    static function level(int $q): string { return $q < 5 ? 'critical' : ($q < 15 ? 'low' : 'normal'); }
    static function all(): array {
        return Database::q('SELECT s.*,f.name facility FROM blood_stocks s JOIN facilities f ON f.id=s.facility_id ORDER BY f.name,s.bloodType')->fetchAll();
    }
    static function urgent(): array {
        return Database::q("SELECT s.*,f.name facility FROM blood_stocks s JOIN facilities f ON f.id=s.facility_id WHERE urgencyLevel<>'normal' ORDER BY urgencyLevel='critical' DESC,quantity")->fetchAll();
    }
    static function upsert(int $f, string $t, int $q, ?string $u = null): void {
        $u = in_array($u, ['normal','low','critical'], true) ? $u : self::level($q);
        Database::q('INSERT INTO blood_stocks(facility_id,bloodType,quantity,urgencyLevel) VALUES(?,?,?,?)
            ON DUPLICATE KEY UPDATE quantity=VALUES(quantity),urgencyLevel=VALUES(urgencyLevel)', [$f, $t, $q, $u]);
    }
    static function delete(int $id): void { Database::q('DELETE FROM blood_stocks WHERE id=?', [$id]); }
}
