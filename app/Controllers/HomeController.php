<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Post;

class HomeController extends BaseController
{
    public function index(): string
    {
        $posts = Post::getRecent(5);

        return $this->renderView(
            template: 'home/index',
            data: [
              'posts' => $posts,
              'title' => 'Home'
            ],
        );
    }
}
