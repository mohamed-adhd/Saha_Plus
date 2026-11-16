<?php
return [
    'GET home' => ['HomeController', 'index'],
    'GET book' => ['BookingController', 'index'],
    'POST book' => ['BookingController', 'book'],
    'POST cancel' => ['BookingController', 'cancel'],
    'GET login' => ['StockController', 'login'],
    'POST login' => ['StockController', 'doLogin'],
    'GET logout' => ['StockController', 'logout'],
    'GET admin' => ['StockController', 'index'],
    'POST stock/save' => ['StockController', 'save'],
    'POST stock/delete' => ['StockController', 'delete'],
    'GET admin/appointments' => ['AppointmentAdminController', 'index'],
    'POST admin/appointments/status' => ['AppointmentAdminController', 'status'],
];
