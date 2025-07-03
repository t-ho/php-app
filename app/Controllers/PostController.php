<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;

class PostController extends BaseController
{
    public function index(): string
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = 5;

        $posts = Post::getRecent($limit, $page, $search);
        $total = Post::count($search);

        return $this->renderView(
            template: 'post/index',
            data: [
              'title' => 'All Posts',
              'posts' => $posts,
              'search' => $search,
              'currentPage' => $page,
              'totalPages' => ceil($total / $limit),
            ],
        );
    }

    public function show(int $id): string
    {
        $post = Post::find($id);

        if (!$post) {
            $this->redirectToNotFound();
        }

        $comments = Comment::forPost($id);
        Post::incrementViews($id);

        return $this->renderView(
            template: 'post/show',
            data: [
                'post' => $post,
                'comments' => $comments,
            ],
        );
    }
}
