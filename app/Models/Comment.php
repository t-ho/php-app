<?php

namespace App\Models;

use Core\App;
use Core\Model;

class Comment extends Model
{
    protected static string $table = 'comments';

    public $id;
    public $content;
    public $user_id;
    public $post_id;
    public $created_at;

    public static function forPost(int $postId): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        return $db->fetchAll(
            query: "SELECT * FROM " . static::$table . " WHERE post_id = ? ORDER BY created_at DESC",
            params: [$postId],
            className: static::class
        );
    }
}
