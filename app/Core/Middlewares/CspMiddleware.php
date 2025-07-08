<?php

namespace App\Core\Middlewares;

use App\Services\CspService;

class CspMiddleware implements MiddlewareInterface
{
    public function handle(callable $next)
    {
        $isDevelopment = ($_ENV['APP_ENV'] ?? 'production') === 'development';
        CspService::setCSPHeader($isDevelopment);

        return $next();
    }
}
