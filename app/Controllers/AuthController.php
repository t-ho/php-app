<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\Auth;
use Core\Router;

class AuthController extends BaseController
{
    public function index(): string
    {
        return $this->renderView(
            template: 'auth/login',
            data: [
              'title' => 'Login'
            ],
        );
    }

    public function login(): string
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) ? (bool)$_POST['remember'] : false;

        if (Auth::attemp($email, $password, $remember)) {
            $this->redirect('/');
        }

        return $this->renderView(
            template: 'auth/login',
            data: [
                'title' => 'Login',
                'error' => 'Invalid credentials.'
            ],
        );
    }

    public function logout(): void
    {
        Auth::logout();

        Router::redirect('/login');
    }
}
