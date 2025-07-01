<?php

namespace App\Controllers;

use Core\View;

class HomeController
{
    public function index(): string
    {
        return View::render(
            template: 'home/index',
            data: [
               'message' => 'This is the home page.'
            ],
            layout: 'layouts/main'
        );
    }
}
