<?php

namespace App\Controllers;

use App\Models\Post;

class HomeController extends BaseController
{
    public function index(): string
    {
        $posts = Post::getRecent(5);

        $this->setTitle('Home');

        return $this->renderView(
            template: 'home/index',
            data: [
              'posts' => $posts,
            ],
            layout: 'layouts/main'
        );
    }
}
