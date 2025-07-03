<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Auth;
use App\Models\User;

class UserController extends BaseController
{
    public function create(array $params): string
    {
        return $this->renderView(
            template: 'user/create',
            data: [
              'title' => 'Register'
            ],
        );
    }

    public function store($params): string
    {
        $data = $this->sanitizeInput(
            inputs: [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'password_confirmation' => $_POST['password_confirmation'] ?? ''
            ],
            excludeKeys: ['password', 'password_confirmation']
        );

        $errors = $this->validateInput($data);

        if (!empty($errors)) {
            return $this->renderView(
                template: 'user/create',
                data: [
                    'title' => 'Register',
                    'errors' => $errors,
                    'old' => $data
                ],
            );
        }

        $user = User::createUser($data);

        if ($user) {
            Auth::attemp($data['email'], $data['password'], false);
            $this->redirect('/');
        }

        return $this->renderView(
            template: 'user/create',
            data: [
                'title' => 'Register',
                'errors' => ['form' => 'Registration failed. Please try again.']
            ],
        );
    }

    public function logout(): void
    {
        Auth::logout();

        $this->redirect('/login');
    }

    protected function sanitizeInput(array $inputs, bool $stripTags = false, array $excludeKeys = []): array
    {
        $sanitized = parent::sanitizeInput($inputs, $stripTags, ['password', 'password_confirmation', ...$excludeKeys]);
        // lowercase email
        $sanitized['email'] = strtolower($inputs['email']);

        return $sanitized;
    }

    protected function validateInput(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif (User::findByEmail($data['email'])) {
            $errors['email'] = 'Email already exists';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (empty($data['password_confirmation'])) {
            $errors['password_confirmation'] = 'Password confirmation is required';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        return $errors;
    }
}
