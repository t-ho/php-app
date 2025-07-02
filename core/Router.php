<?php

namespace Core;

class Router
{
    protected array $routes = [];
    protected array $globalMiddleware = [];
    protected array $routeMiddleware = [];

    public function add(string $method, string $path, string $controller, array $middlewares = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'middlewares' => $middlewares,
        ];
    }

    public function addGlobalMiddleware(string $middleware): void
    {
        $this->globalMiddleware[] = $middleware;
    }

    public function addRouteMiddleware(string $name, string $middleware): void
    {
        $this->routeMiddleware[$name] = $middleware;
    }

    public static function notFound(): void
    {
        http_response_code(404);
        echo View::render(
            template: 'errors/404',
        );
        exit;
    }

    public static function unauthorized(): void
    {
        http_response_code(401);
        echo View::render(
            template: 'errors/401',
        );
        exit;
    }


    public static function pageExpired(): void
    {
        http_response_code(419);
        echo View::render(
            template: 'errors/419',
        );
        exit;
    }

    public static function redirect(string $uri): void
    {
        header("Location: {$uri}");
        exit;
    }

    public function dispatch(string $path, string $method): string
    {
        $route = $this->findRoute($path, $method);

        if (!$route) {
            static::notFound();
        }

        $middlewares = [
            ...$this->globalMiddleware,
            ...array_map(fn ($name) => $this->routeMiddleware[$name], $route['middlewares'])
        ];

        return $this->runMiddleware($middlewares, function () use ($route) {
            [$controller, $action] = explode('@', $route['controller']);

            return  $this->callAction($controller, $action, $route['params'] ?? []);
        });
    }

    protected function runMiddleware(array $middlewares, callable $target): mixed
    {
        $next = $target;

        foreach (array_reverse($middlewares) as $middleware) {
            $next = fn () => (new $middleware())->handle($next);
        }

        return $next();
    }

    protected function findRoute(string $path, string $method): ?array
    {
        foreach ($this->routes as $route) {
            $params = $this->matchRoute($route['path'], $path);
            if ($params !== null && $route['method'] === $method) {
                return [...$route, 'params' => $params];
            }
        }

        return null;
    }

    protected function matchRoute(string $routePath, string $requestPath): ?array
    {
        $routeSegments = explode('/', trim($routePath, '/'));
        $requestSegments = explode('/', trim($requestPath, '/'));

        if (count($routeSegments) !== count($requestSegments)) {
            return null;
        }

        $params = [];
        foreach ($routeSegments as $index => $routeSegment) {
            if (str_starts_with($routeSegment, '{') && str_ends_with($routeSegment, '}')) {
                $params[trim($routeSegment, '{}')] = $requestSegments[$index];
            } elseif ($routeSegment !== $requestSegments[$index]) {
                return null;
            }
        }

        return $params;
    }

    protected function callAction(string $controller, string $action, array $params = []): string
    {
        $controllerClass = "App\\Controllers\\{$controller}";
        return (new $controllerClass())->$action(...$params);
    }
}
