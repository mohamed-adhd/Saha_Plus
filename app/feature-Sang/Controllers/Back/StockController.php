<?php
class StockController {
    function login() { view('back/login'); }
    function doLogin() {
        if (hash_equals($GLOBALS['config']['admin_password'], $_POST['password'] ?? '')) { $_SESSION['admin'] = 1; redirect('admin'); }
        flash('Mot de passe incorrect.', 'err'); redirect('login');
    }
    function logout() { unset($_SESSION['admin']); redirect('home'); }
    function index() {
        require_admin();
        view('back/stocks', ['stocks' => BloodStock::all(), 'facilities' => Facility::all()], 'layout');
    }
    function save() {
        require_admin();
        $t = $_POST['bloodType'] ?? '';
        if (in_array($t, BloodStock::TYPES, true)) {
            BloodStock::upsert((int)$_POST['facility_id'], $t, max(0, (int)$_POST['quantity']), $_POST['urgencyLevel'] ?? null);
            flash('Stock enregistré.');
        }
        redirect('admin');
    }
    function delete() { require_admin(); BloodStock::delete((int)($_POST['id'] ?? 0)); redirect('admin'); }
}
