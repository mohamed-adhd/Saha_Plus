<?php
return [
    // Front Office (shared shell: sidebar + topbar)
    'home' => [HomeController::class, 'index'],
    'coming-soon' => [HomeController::class, 'comingSoon'],
    'components' => [ComponentsController::class, 'index'],
    // Back Office (shared admin shell)
    'dashboard' => [DashboardController::class, 'index'],
];
