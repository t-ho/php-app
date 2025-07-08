<?php

namespace App\Core\Middlewares;

use App\Core\Router;
use App\Services\AuthService;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        $user = AuthService::user();
        if (!$user) {
            Router::unauthorized();
        }

        return $next();
    }
}
