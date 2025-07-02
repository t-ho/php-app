<?php

namespace App\Middlewares;

use App\Services\Auth;
use Core\Middleware;
use Core\View;

class ViewMiddleware implements Middleware
{
    public function handle(callable $next)
    {
        View::share('user', Auth::user());

        return $next();
    }
}
