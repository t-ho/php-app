<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use App\Services\Auth;
use App\Services\Authorization;

class PostController extends AdminBaseController
{
    public function index(array $params): string
    {
        Authorization::ensureAuthorized('access_manage_posts');

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = 5;

        $posts = Post::getRecent($limit, $page, $search);
        $total = Post::count($search);

        return $this->renderView(
            template: 'admin/post/index',
            data: [
              'title' => 'Manage Posts',
              'posts' => $posts,
              'search' => $search,
              'currentPage' => $page,
              'totalPages' => ceil($total / $limit),
            ],
        );
    }

    public function create(array $params): string
    {
        Authorization::ensureAuthorized('create_post');

        return $this->renderView(
            template: 'admin/post/create',
        );
    }

    public function store($id): void
    {
        Authorization::ensureAuthorized('create_post');

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        Post::create([
            'title' => $title,
            'content' => $content,
            'user_id' => Auth::user()->id,
        ]);

        $this->redirect('/admin/posts');
    }

    public function edit(array $params): string
    {
        $post = Post::findOrFail($params['postId']);
        Authorization::ensureAuthorized('update_post', $post);

        return $this->renderView(
            template: 'admin/post/edit',
            data: [
              'post' => $post,
            ],
        );
    }

    public function update(array $params): void
    {
        $post = Post::findOrFail($params['postId']);

        Authorization::ensureAuthorized('update_post', $post);

        $post->title = $_POST['title'] ?? '';
        $post->content = $_POST['content'] ?? '';

        $post->save();

        $this->redirect('/admin/posts');
    }

    public function destroy(array $params): void
    {
        $post = Post::findOrFail($params['postId']);

        Authorization::ensureAuthorized('delete_post', $post);

        $post->delete();

        $this->redirect('/admin/posts');
    }
}
