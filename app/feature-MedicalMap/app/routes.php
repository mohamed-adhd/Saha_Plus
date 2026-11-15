<?php
/*
 * Route table: page key (index.php?page=KEY) => [Controller class, method].
 */
return [
    // Front Office
    'map' => [MapController::class, 'index'],
    'map-data' => [MapController::class, 'data'],             // JSON read by assets/js/map.js
    'coming-soon' => [MapController::class, 'comingSoon'],   // target of the shared sidebar links

    // Back Office – facilities
    'dashboard' => [FacilityAdminController::class, 'index'], // the shared admin brand links to "dashboard"
    'admin-facilities' => [FacilityAdminController::class, 'index'],
    'admin-facility-form' => [FacilityAdminController::class, 'form'],     // add (no id) or edit (?id=)
    'admin-facility-save' => [FacilityAdminController::class, 'save'],     // POST
    'admin-facility-delete' => [FacilityAdminController::class, 'delete'], // POST

    // Back Office – doctors
    'admin-doctors' => [DoctorAdminController::class, 'index'],
    'admin-doctor-form' => [DoctorAdminController::class, 'form'],
    'admin-doctor-save' => [DoctorAdminController::class, 'save'],
    'admin-doctor-delete' => [DoctorAdminController::class, 'delete'],
];
