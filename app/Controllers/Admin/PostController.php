<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use App\Services\Auth;
use App\Services\Authorization;
use Core\Router;
use Core\View;

class PostController
{
    public function index(): string
    {
        Authorization::ensureAuthorized('access_manage_posts');

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = 5;

        $posts = Post::getRecent($limit, $page, $search);
        $total = Post::count($search);

        return View::render(
            template: 'admin/post/index',
            data: [
              'posts' => $posts,
              'search' => $search,
              'currentPage' => $page,
              'totalPages' => ceil($total / $limit),
            ],
            layout: 'layouts/admin'
        );
    }

    public function create(): string
    {
        Authorization::ensureAuthorized('create_post');

        return View::render(
            template: 'admin/post/create',
            layout: 'layouts/admin'
        );
    }

    public function store(): void
    {
        Authorization::ensureAuthorized('create_post');

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        Post::create([
            'title' => $title,
            'content' => $content,
            'user_id' => Auth::user()->id,
        ]);

        Router::redirect('/admin/posts');
    }

    public function edit(int $id): string
    {
        $post = Post::findOrFail($id);
        Authorization::ensureAuthorized('update_post', $post);

        return View::render(
            template: 'admin/post/edit',
            data: [
              'post' => $post,
            ],
            layout: 'layouts/admin'
        );
    }

    public function update(int $id): void
    {
        $post = Post::findOrFail($id);

        Authorization::ensureAuthorized('update_post', $post);

        $post->title = $_POST['title'] ?? '';
        $post->content = $_POST['content'] ?? '';

        $post->save();

        Router::redirect('/admin/posts');
    }

    public function delete(int $id): void
    {
        $post = Post::findOrFail($id);

        Authorization::ensureAuthorized('delete_post', $post);

        $post->delete();

        Router::redirect('/admin/posts');
    }
}
