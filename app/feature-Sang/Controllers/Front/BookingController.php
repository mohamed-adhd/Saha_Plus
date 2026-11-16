<?php
class BookingController {
    function index() {
        view('front/book', ['facilities' => Facility::all(), 'mine' => DonationAppointment::mine(uid()), 'sel' => (int)($_GET['facility'] ?? 0)]);
    }
    function book() {
        $anon = !empty($_POST['anonymous']);
        $t = $_POST['bloodType'] ?? ''; $f = (int)($_POST['facility_id'] ?? 0); $d = $_POST['date'] ?? '';
        $n = trim($_POST['name'] ?? ''); $c = trim($_POST['contact'] ?? '');
        if (!in_array($t, BloodStock::TYPES, true) || !$f || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) || $d < date('Y-m-d') || (!$anon && ($n === '' || $c === ''))) {
            flash('Formulaire invalide.', 'err'); redirect('book');
        }
        DonationAppointment::create([uid(), $f, $t, $d, (int)$anon, $anon ? null : $n, $anon ? null : $c]);
        flash('Rendez-vous enregistré (en attente de confirmation).'); redirect('book');
    }
    function cancel() {
        DonationAppointment::cancel((int)($_POST['id'] ?? 0), uid());
        flash('Rendez-vous annulé.'); redirect('book');
    }
}
