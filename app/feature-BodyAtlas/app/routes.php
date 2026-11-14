<?php
return [
    // Front Office
    'body-atlas' => [BodyAtlasController::class, 'index'],
    'organ' => [BodyAtlasController::class, 'show'],
    'coming-soon' => [BodyAtlasController::class, 'comingSoon'],
    'login' => [AuthController::class, 'login'],
    'register' => [AuthController::class, 'register'],
    // Back Office
    'admin-organs' => [OrganAdminController::class, 'index'],
    'admin-symptoms' => [SymptomAdminController::class, 'index'],
    'admin-users' => [UserAdminController::class, 'index'],
];
