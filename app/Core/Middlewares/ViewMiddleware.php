<?php

namespace App\Core\Middlewares;

use App\Core\View;
use App\Services\AuthService;

class ViewMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        View::share('user', AuthService::user());

        return $next();
    }
}
