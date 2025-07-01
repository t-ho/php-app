<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use Core\Router;

$router = new Router();

require_once __DIR__ . '/../routes.php';
foreach (glob(__DIR__ . '/../app/Helpers/*.php') as $file) {
    require_once $file;
}

$uri  = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

echo $router->dispatch(uri: $uri, method: $method);
