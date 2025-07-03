<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\Auth;
use Core\Router;

class AuthController extends BaseController
{
    public function index(): string
    {
        $this->setTitle('Login');

        return $this->renderView(
            template: 'auth/create',
            layout: 'layouts/main'
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
            template: 'auth/create',
            data: [
                'error' => 'Invalid credentials.'
            ],
            layout: 'layouts/main'
        );
    }

    public function logout(): void
    {
        Auth::logout();

        Router::redirect('/login');
    }
}
