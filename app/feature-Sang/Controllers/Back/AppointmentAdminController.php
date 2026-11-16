<?php
class AppointmentAdminController {
    function index() { require_admin(); view('back/appointments', ['appts' => DonationAppointment::all()], 'layout'); }
    function status() {
        require_admin();
        if (in_array($_POST['status'] ?? '', DonationAppointment::STATUS, true)) DonationAppointment::setStatus((int)$_POST['id'], $_POST['status']);
        redirect('admin/appointments');
    }
}
