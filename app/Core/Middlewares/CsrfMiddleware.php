<?php

namespace App\Core\Middlewares;

use App\Core\Router;
use App\Services\Csrf;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Routes that should be excluded from CSRF protection
     */
    private const EXCLUDED_ROUTES = [
        '/csp-report',  // CSP violation reports don't include CSRF tokens
    ];

    public function handle(callable $next)
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $path = parse_url($requestUri, PHP_URL_PATH);

        // Skip CSRF validation for excluded routes
        if (in_array($path, self::EXCLUDED_ROUTES, true)) {
            return $next();
        }

        if (!Csrf::isTokenValid()) {
            Router::pageExpired();
        }

        return $next();
    }
}
