<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Comment;
use App\Services\AuthService;
use App\Services\AuthorizationService;

class CommentController extends BaseController
{
    public function store(array $params): void
    {
        AuthorizationService::ensureAuthorized('create_comment');

        $content = $_POST['content'] ?? '';
        Comment::create([
            'post_id' => $params['postId'],
            'user_id' => AuthService::user()->id,
            'content' => $content,
        ]);

        $this->redirect("/posts/{$params['postId']}#comments");
    }
}
