<?php

namespace App\Services;

use App\Models\Comment;
use App\Core\Router;
use App\Services\AuthService;

class AuthorizationService
{
    public static function ensureAuthorized(string $action, mixed $resource = null): void
    {
        if (!self::isAuthorizedFor($action, $resource)) {
            Router::forbidden();
        }
    }

    public static function isAuthorizedFor(string $action, mixed $resource = null): bool
    {
        $user = AuthService::user();
        if (!$user) {
            return false; // Not logged in
        }

        if ($user->role === 'admin') {
            return true; // Admins can do anything
        }

        return match ($action) {
            'create_comment' => true,
            'update_comment', 'delete_commment' => $resource instanceof Comment && ($user->id === $resource->user_id),
            'access_dashboard' => $user->role === 'admin',
            default => false,
        };
    }
}
