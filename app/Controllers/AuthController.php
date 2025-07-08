<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function index(array $params): string
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
        $data = $this->sanitizeInput(
            inputs: [
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'remember' => isset($_POST['remember']) ? (bool)$_POST['remember'] : false
            ],
            excludeKeys: ['password']
        );

        if (AuthService::attemp($data['email'], $data['password'], $data['remember'])) {
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
        AuthService::logout();

        $this->redirect('/login');
    }
}
