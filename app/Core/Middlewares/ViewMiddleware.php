<?php

namespace App\Core\Middlewares;

use App\Core\View;
use App\Services\Auth;

class ViewMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        View::share('user', Auth::user());

        return $next();
    }
}
