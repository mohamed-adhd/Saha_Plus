<?php
declare(strict_types=1);

final class AuthController extends Controller
{
    public function login(): void
    {
        $title = 'Log in';
        $currentPage = '';
        $frontView = 'login';
        require __DIR__ . '/../../../includes/front-layout.php';
    }

    public function register(): void
    {
        $title = 'Create an account';
        $currentPage = '';
        $frontView = 'register';
        $bloodTypes = ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'];
        require __DIR__ . '/../../../includes/front-layout.php';
    }
}
