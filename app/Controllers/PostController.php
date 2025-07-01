<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Core\Router;
use Core\View;

class PostController
{
    public function index(): string
    {
        return 'List of Posts';
    }

    public function show(string $id): string
    {
        $post = Post::find($id);

        if (!$post) {
            Router::notFound();
        }

        $comments = Comment::forPost($id);
        Post::incrementViews($id);

        return View::render(
            template: 'post/show',
            data: [
                'post' => $post,
                'comments' => $comments,
            ],
            layout: 'layouts/main'
        );
    }
}
