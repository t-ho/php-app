<?php

namespace App\Middlewares;

use App\Services\Auth;
use Core\View;

class ViewMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        View::share('user', Auth::user());

        return $next();
    }
}
