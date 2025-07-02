<?php

namespace App\Middlewares;

use App\Services\Csrf;
use Core\Middleware;
use Core\Router;

class CsrfMiddleware implements Middleware
{
    public function handle(callable $next)
    {
        if (!Csrf::isTokenValid()) {
            Router::pageExpired();
        }

        return $next();
    }
}
