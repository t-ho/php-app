<?php

/**
* @var Core\Router $router
*/

use App\Core\Middlewares\AuthMiddleware;
use App\Core\Middlewares\CsrfMiddleware;
use App\Core\Middlewares\ViewMiddleware;

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
    path: '/posts/{postId}',
    controller: 'PostController@show'
);
$router->add(
    method: 'POST',
    path: '/posts/{postId}/comments',
    controller: 'CommentController@store',
    middlewares: ['auth']
);

// Authentication routes
$router->add(
    method: 'GET',
    path: '/login',
    controller: 'AuthController@index'
);
$router->add(
    method: 'POST',
    path: '/login',
    controller: 'AuthController@login'
);
$router->add(
    method: 'POST',
    path: '/logout',
    controller: 'AuthController@logout'
);

// User routes
$router->add(
    method: 'GET',
    path: '/register',
    controller: 'UserController@create'
);
$router->add(
    method: 'POST',
    path: '/register',
    controller: 'UserController@store'
);

// Admin routes
$router->add(
    method: 'GET',
    path: '/admin/dashboard',
    controller: 'Admin\DashboardController@index',
    middlewares: ['auth']
);
$router->add(
    method: 'GET',
    path: '/admin/posts',
    controller: 'Admin\PostController@index',
    middlewares: ['auth']
);
$router->add(
    method: 'GET',
    path: '/admin/posts/create',
    controller: 'Admin\PostController@create',
    middlewares: ['auth']
);
$router->add(
    method: 'POST',
    path: '/admin/posts',
    controller: 'Admin\PostController@store',
    middlewares: ['auth']
);
$router->add(
    method: 'GET',
    path: '/admin/posts/{postId}/edit',
    controller: 'Admin\PostController@edit',
    middlewares: ['auth']
);
$router->add(
    method: 'POST',
    path: '/admin/posts/{postId}',
    controller: 'Admin\PostController@update',
    middlewares: ['auth']
);
$router->add(
    method: 'POST',
    path: '/admin/posts/{postId}/delete',
    controller: 'Admin\PostController@destroy',
    middlewares: ['auth']
);
$router->add(
    method: 'POST',
    path: '/admin/upload-image',
    controller: 'Admin\PostController@uploadImage',
    middlewares: ['auth']
);
