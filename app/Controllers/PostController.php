<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Comment;
use App\Models\Post;

class PostController extends BaseController
{
    public function index($params): string
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

    public function show(array $params): string
    {
        $post = Post::find($params['postId']);

        if (!$post) {
            $this->redirectToNotFound();
        }

        $comments = Comment::forPost($params['postId']);
        Post::incrementViews($params['postId']);

        return $this->renderView(
            template: 'post/show',
            data: [
                'title' => $post->title,
                'post' => $post,
                'comments' => $comments,
            ],
        );
    }
}
