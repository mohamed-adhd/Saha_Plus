<?php
declare(strict_types=1);

final class UserAdminController extends Controller
{
    public function index(): void
    {
        $page = 'back/users'; $title = 'Users'; $currentPage = 'admin-users';
        $users = User::all();
        $bloodTypes = ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'];
        $this->viewData = compact('page', 'title', 'currentPage', 'users', 'bloodTypes');
        $this->render($page);
    }
}
