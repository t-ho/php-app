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

$router->add(
    method: 'GET',
    path: '/',
    controller: 'HomeController@index'
);
$router->add(
    method: 'GET',
    path: '/posts',
    controller: 'PostController@index'
);
$router->add(
    method: 'GET',
    path: '/posts/{id}',
    controller: 'PostController@show'
);
$router->add(
    method: 'POST',
    path: '/posts/{id}/comments',
    controller: 'CommentController@store',
    middlewares: ['auth']
);

$router->add(
    method: 'GET',
    path: '/login',
    controller: 'AuthController@create'
);
$router->add(
    method: 'POST',
    path: '/login',
    controller: 'AuthController@store'
);
$router->add(
    method: 'POST',
    path: '/logout',
    controller: 'AuthController@destroy'
);

// Admin routes
$router->add(
    method: 'GET',
    path: '/admin/dashboard',
    controller: 'Admin\DashboardController@index',
    middlewares: ['auth']
);
