<?php

/**
* @var Core\Router $router
*/

use App\Middlewares\ViewMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\CsrfMiddleware;

$router->addGlobalMiddleware(ViewMiddleware::class);
$router->addGlobalMiddleware(CsrfMiddleware::class);
$router->addRouteMiddleware('auth', AuthMiddleware::class);

$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/posts', 'PostController@index');
$router->add('GET', '/posts/{id}', 'PostController@show');
$router->add('POST', '/posts/{id}/comments', 'CommentController@store', ['auth']);

$router->add('GET', '/login', 'AuthController@create');
$router->add('POST', '/login', 'AuthController@store');
$router->add('POST', '/logout', 'AuthController@destroy');

// Admin routes
$router->add('GET', '/admin/dashboard', 'Admin\DashboardController@index', ['auth']);
