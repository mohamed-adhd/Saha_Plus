<?php
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function url(string $r = 'home'): string { return 'index.php?r=' . $r; }
function redirect(string $r) { header('Location: ' . url($r)); exit; }
function flash(string $m, string $t = 'ok') { $_SESSION['flash'] = [$m, $t]; }
function uid(): string { return $_SESSION['uid'] ??= bin2hex(random_bytes(8)); }
function require_admin() { if (empty($_SESSION['admin'])) redirect('login'); }
function asset(string $p): string { return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/assets/' . $p; }
function shared(string $p): string { return rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/shared/' . $p; }
function compat(): array {
    return ['O-' => ['O-','O+','A-','A+','B-','B+','AB-','AB+'], 'O+' => ['O+','A+','B+','AB+'],
        'A-' => ['A-','A+','AB-','AB+'], 'A+' => ['A+','AB+'], 'B-' => ['B-','B+','AB-','AB+'],
        'B+' => ['B+','AB+'], 'AB-' => ['AB-','AB+'], 'AB+' => ['AB+']];
}
function view(string $page, array $d = [], string $layout = 'layout_front') {
    extract($d); ob_start();
    require ROOT . "/app/Views/pages/$page.php";
    $content = ob_get_clean();
    require ROOT . "/app/Views/$layout.php";
}
