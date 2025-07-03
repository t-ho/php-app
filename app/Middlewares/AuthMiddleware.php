<?php

namespace App\Middlewares;

use App\Services\Auth;
use Core\Router;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        $user = Auth::user();
        if (!$user) {
            Router::unauthorized();
        }

        return $next();
    }
}
