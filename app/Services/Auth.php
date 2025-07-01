<?php

namespace App\Services;

use App\Models\User;

class Auth
{
    public static $user = null;

    public static function attemp(string $email, string $password, bool $remember = false): bool
    {
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            // generate a new session ID to prevent session fixation attacks
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->id;

            if ($remember) {
                RememberMe::createToken($user->id);
            }

            return true;
        }

        return false;
    }

    public static function user(): ?User
    {
        if (static::$user === null) {
            $userId = $_SESSION['user_id'] ?? null;

            static::$user = $userId ? User::find($userId) : RememberMe::user();
        }

        return static::$user;
    }

    public static function logout(): void
    {
        RememberMe::clearToken();
        $_SESSION = [];
        session_destroy();
        static::$user = null;
    }
}
