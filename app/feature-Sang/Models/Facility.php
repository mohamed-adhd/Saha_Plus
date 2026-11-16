<?php
class Facility {
    static function all(): array { return Database::q('SELECT * FROM facilities ORDER BY name')->fetchAll(); }
    static function withNeeds(): array {
        $f = self::all();
        $n = Database::q("SELECT facility_id,bloodType,urgencyLevel FROM blood_stocks WHERE urgencyLevel<>'normal'")->fetchAll();
        foreach ($f as &$x) {
            $x['lat'] = (float)$x['lat']; $x['lng'] = (float)$x['lng']; $x['accessible'] = (bool)$x['accessible'];
            $x['needs'] = array_values(array_filter($n, fn($r) => $r['facility_id'] == $x['id']));
        }
        return $f;
    }
}
