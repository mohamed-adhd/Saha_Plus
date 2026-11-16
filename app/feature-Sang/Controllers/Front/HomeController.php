<?php
class HomeController {
    function index() {
        view('front/home', ['facilities' => Facility::withNeeds(), 'urgent' => BloodStock::urgent(), 'comp' => compat()]);
    }
}
