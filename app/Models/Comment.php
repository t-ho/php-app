<?php

namespace App\Models;

use App\Core\App;
use App\Core\Model;

class Comment extends Model
{
    protected static string $table = 'comments';

    public $id;
    public $content;
    public $user_id;
    public $post_id;
    public $created_at;
    public $user_name;

    public static function forPost(int $postId): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        return $db->fetchAll(
            query: "SELECT c.*, u.name as user_name FROM " . static::$table . " c 
                   JOIN " . User::getTable() . " u ON c.user_id = u.id 
                   WHERE c.post_id = ? ORDER BY c.created_at DESC",
            params: [$postId],
            className: static::class
        );
    }
}
