<?php

namespace App\Core\Middlewares;

interface MiddlewareInterface
{
    public function handle(callable $next);
}