<?php

namespace App\Middlewares;

use App\Services\Auth;
use Core\Middleware;
use Core\Router;

class AuthMiddleware implements Middleware
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
