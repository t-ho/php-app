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
        //TODO: CSRF toekn validation
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attemp($email, $password)) {
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
