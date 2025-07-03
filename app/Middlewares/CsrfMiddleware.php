<?php

namespace App\Middlewares;

use App\Services\Csrf;
use Core\Router;

class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        if (!Csrf::isTokenValid()) {
            Router::pageExpired();
        }

        return $next();
    }
}
