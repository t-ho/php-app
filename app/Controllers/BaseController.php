<?php

namespace App\Controllers;

use Core\App;
use Core\Router;
use Core\View;

abstract class BaseController
{
    protected array $data = [];

    public function __construct()
    {
        $this->data['title'] = App::get('config')['app']['name'];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $id)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->notImplemented(__METHOD__);
    }

    /**
     * Set the page title
     */
    protected function setTitle(?string $title): void
    {
        $this->data['title'] = ($title ? $title . ' - ' : '') . App::get('config')['app']['name'];
    }

    /**
     * Add data to be passed to views
     */
    protected function addData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Merge data array with existing data
     */
    protected function mergeData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Render a view with the controller's data
     */
    protected function renderView(string $template, array $data = [], ?string $layout = null): string
    {
        return View::render($template, array_merge($this->data, $data), $layout);
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
