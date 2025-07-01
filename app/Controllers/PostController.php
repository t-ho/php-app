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
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = 5;

        $posts = Post::getRecent($limit, $page, $search);
        $total = Post::count($search);

        return View::render(
            template: 'post/index',
            data: [
              'posts' => $posts,
              'search' => $search,
              'currentPage' => $page,
              'totalPages' => ceil($total / $limit),
            ],
            layout: 'layouts/main'
        );
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
