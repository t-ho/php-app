<?php

namespace App\Core\Middlewares;

use App\Core\Router;
use App\Services\Auth;

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
