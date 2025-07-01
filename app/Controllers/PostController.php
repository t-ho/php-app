<?php

namespace App\Controllers;

class PostController
{
    public function index(): string
    {
        return 'List of Posts';
    }

    public function show(string $id): string
    {
        return "Showing post with ID: {$id}";
    }
}
