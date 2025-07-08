<?php

namespace App\Services;

class CsrfService
{
    private const CSRF_TOKEN_LENGTH = 32;
    private const CSRF_TOKEN_LIFETIME = 30 * 60; // 30 minutes in seconds
    public const CSRF_TOKEN_NAME = 'csrf_token';


    public static function getToken(): ?string
    {
        if (!isset($_SESSION['csrf_token']) || self::isTokenExpired()) {
            return self::generateToken();
        }

        return $_SESSION['csrf_token']['token'];
    }

    public static function isTokenValid(?string $token = null): bool
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return true; // No CSRF check for safe methods
        }

        $csrfToken = $token ?? $_POST[self::CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        if (
            !empty($csrfToken)
            && !self::isTokenExpired()
            && hash_equals($_SESSION['csrf_token']['token'] ?? '', $csrfToken)
        ) {
            return true; // CSRF token is valid
        }

        return false; // CSRF token is invalid or expired
    }

    private static function isTokenExpired(): bool
    {
        $expires = $_SESSION['csrf_token']['expires'];
        return !isset($expires) || $expires <= time();
    }

    private static function generateToken(): string
    {
        $token = bin2hex(random_bytes(self::CSRF_TOKEN_LENGTH));
        $_SESSION['csrf_token'] = [
          'token' => $token,
          'expires' => time() + self::CSRF_TOKEN_LIFETIME
        ];
        return $token;
    }
}
