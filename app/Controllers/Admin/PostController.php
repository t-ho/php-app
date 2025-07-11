<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use App\Models\UploadedImage;
use App\Services\AuthService;
use App\Services\AuthorizationService;
use App\Services\ImageUploadService;

class PostController extends AdminBaseController
{
    private const DEFAULT_PAGE_LIMIT = 10;

    public function index(array $params): string
    {
        AuthorizationService::ensureAuthorized('access_manage_posts');

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $posts = Post::getRecent(self::DEFAULT_PAGE_LIMIT, $page, $search);
        $total = Post::count($search);

        return $this->renderView(
            template: 'admin/post/index',
            data: [
              'title' => 'Manage Posts',
              'posts' => $posts,
              'search' => $search,
              'currentPage' => $page,
              'totalPages' => ceil($total / self::DEFAULT_PAGE_LIMIT),
            ],
        );
    }

    public function create(array $params): string
    {
        AuthorizationService::ensureAuthorized('create_post');

        return $this->renderView(
            template: 'admin/post/create',
            data: [
              'title' => 'Create New Post',
            ],
        );
    }

    public function store(array $params): void
    {
        AuthorizationService::ensureAuthorized('create_post');

        $data = $this->sanitizeInput(['title' => $_POST['title'] ?? '', 'content' => $_POST['content'] ?? '']);
        $sanitizedContent = sanitizeHtml($data['content']);

        $createdPost = Post::create([
            'title' => $data['title'],
            'sanitized_html_content' => $sanitizedContent,
            'user_id' => AuthService::user()->id,
        ]);

        if ($createdPost) {
            // Sync images in content with database tracking
            UploadedImage::syncPostImages($createdPost->id, $createdPost->sanitized_html_content);
        }

        $this->redirect('/admin/posts');
    }

    public function edit(array $params): string
    {
        $post = Post::findOrFail($params['postId']);
        AuthorizationService::ensureAuthorized('update_post', $post);

        return $this->renderView(
            template: 'admin/post/edit',
            data: [
              'title' => 'Edit Post: ' . $post->title,
              'post' => $post,
            ],
        );
    }

    public function update(array $params): void
    {
        $post = Post::findOrFail($params['postId']);

        AuthorizationService::ensureAuthorized('update_post', $post);

        $data = $this->sanitizeInput(['title' => $_POST['title'] ?? '', 'content' => $_POST['content'] ?? '']);
        $post->title = $data['title'];
        $post->sanitized_html_content = sanitizeHtml($data['content']);

        $post->save();

        // Sync images in content with database tracking
        UploadedImage::syncPostImages($post->id, $post->sanitized_html_content);

        $this->redirect('/admin/posts');
    }

    public function destroy(array $params): void
    {
        $post = Post::findOrFail($params['postId']);

        AuthorizationService::ensureAuthorized('delete_post', $post);

        // Sync images before deletion to clean up orphaned images
        UploadedImage::syncPostImages($post->id, '');

        $post->delete();

        $this->redirect('/admin/posts');
    }

    public function uploadImage(): void
    {
        try {
            AuthorizationService::ensureAuthorized('create_post');

            if (!isset($_FILES['file'])) {
                $this->json(['error' => 'No file uploaded'], 400);
            }

            $imageUrl = ImageUploadService::uploadPostImage($_FILES['file']);

            // TinyMCE expects 'location' field for the image URL
            $this->json(['location' => $imageUrl]);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
