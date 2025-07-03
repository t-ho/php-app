<?php

namespace App\Core\Middlewares;

use App\Core\Router;
use App\Services\Csrf;

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
