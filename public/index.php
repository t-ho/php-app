<?php

declare(strict_types=1);

use App\Core\Router;

require_once __DIR__ . '/../bootstrap.php';
session_start();

$router = new Router();

require_once __DIR__ . '/../routes.php';
foreach (glob(__DIR__ . '/../app/Helpers/*.php') as $file) {
    require_once $file;
}

$path = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

echo $router->dispatch(path: $path, method: $method);
