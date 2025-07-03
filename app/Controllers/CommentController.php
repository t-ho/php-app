<?php

namespace App\Controllers;

use App\Core\BaseController;;
use App\Models\Comment;
use App\Services\Auth;
use App\Services\Authorization;

class CommentController extends BaseController
{
    public function store(int $id): void
    {
        Authorization::ensureAuthorized('create_comment');

        $content = $_POST['content'] ?? '';
        Comment::create([
            'post_id' => $id,
            'user_id' => Auth::user()->id,
            'content' => $content,
        ]);

        $this->redirect("/posts/{$id}#comments");
    }
}
