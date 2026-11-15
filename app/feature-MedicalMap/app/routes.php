<?php
/*
 * Route table: page key (index.php?page=KEY) => [Controller class, method].
 */
return [
    // Front Office
    'map' => [MapController::class, 'index'],
    'map-data' => [MapController::class, 'data'],             // JSON read by assets/js/map.js
    'coming-soon' => [MapController::class, 'comingSoon'],   // target of the shared sidebar links
    // Back Office
    'dashboard' => [FacilityAdminController::class, 'index'], // the shared admin brand links to "dashboard"
    'admin-facilities' => [FacilityAdminController::class, 'index'],
    'admin-doctors' => [DoctorAdminController::class, 'index'],
];
