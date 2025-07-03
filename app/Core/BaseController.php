<?php

namespace App\Core;

use App\Core\App;
use App\Core\Router;
use App\Core\View;

abstract class BaseController
{
    private string $defaultTitleSuffix;
    private string $defaultLayout;

    public function __construct()
    {
        $this->defaultTitleSuffix = App::get('config')['app']['name'];
        $this->defaultLayout = 'layouts/main';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(array $params)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Set the default title suffix
     */
    protected function setDefaultTitleSuffix(string $titleSuffix): void
    {
        $this->defaultTitleSuffix = $titleSuffix ? $titleSuffix : App::get('config')['app']['name'];
    }

    /**
     * Set the default layout
     */
    protected function setDefaultLayout(string $layout): void
    {
        $this->defaultLayout = $layout;
    }

    /**
    * Create a page title
    */
    protected function createTitle(string $title): string
    {
        return $title ? $title . ' - ' . $this->defaultTitleSuffix : $this->defaultTitleSuffix;
    }

    /**
     * Render a view with the controller's data
     */
    protected function renderView(string $template, array $data = [], ?string $layout = null): string
    {
        $data = [...$data, 'title' => static::createTitle($data['title'] ?? '')];
        $layout = $layout ?? $this->defaultLayout;

        return View::render($template, $data, $layout);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        Router::redirect($url);
    }

    /**
    * Redirect to Not Found page - 404
    */
    protected function redirectToNotFound(): void
    {
        Router::notFound();
    }

    /**
    * Redirect to Unauthorized page - 401
    */
    protected function redirectToUnauthorized(): void
    {
        Router::unauthorized();
    }

    /**
    * Redirect to Forbidden page - 403
    */
    protected function redirectToForbidden(): void
    {
        Router::forbidden();
    }

    /**
    * Redirect to Page Expired page - 419
    */
    protected function redirectToPageExpired(): void
    {
        Router::pageExpired();
    }

    /**
    * Sanitize input data
    */
    protected function sanitizeInput(array $inputs, bool $stripTags = false, array $excludeKeys = []): array
    {
        $sanitized = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, $excludeKeys, true)) {
                // Skip sanitization for excluded keys
                $sanitized[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                // Recursively sanitize nested arrays
                $sanitized[$key] = $this->sanitizeInput($value, $stripTags, $excludeKeys);
            } elseif (is_string($value)) {
                $value = trim($value);
                if ($stripTags) {
                    $value = strip_tags($value);
                }
                $sanitized[$key] = $value;
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Handle not implemented methods
     */
    private function notImplemented(string $method): void
    {
        http_response_code(501);
        echo "Method $method not implemented in " . static::class;
        exit;
    }
}
