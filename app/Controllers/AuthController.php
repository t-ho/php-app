<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\Auth;
use Core\Router;
use Core\View;

class AuthController
{
    public function create(): string
    {
        return View::render(
            template: 'auth/create',
            data: [],
            layout: 'layouts/main'
        );
    }

    public function store(): string
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) ? (bool)$_POST['remember'] : false;

        if (Auth::attemp($email, $password, $remember)) {
            Router::redirect('/');
        }

        return View::render(
            template: 'auth/create',
            data: [
                'error' => 'Invalid credentials.'
            ],
            layout: 'layouts/main'
        );
    }

    public function destroy(): void
    {
        Auth::logout();

        Router::redirect('/login');
    }
}
