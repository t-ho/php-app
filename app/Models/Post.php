<?php

namespace App\Models;

use Core\App;
use Core\Model;

class Post extends Model
{
    protected static string $table = 'posts';

    public $id;
    public $title;
    public $content;
    public $created_at;
    public $user_id;
    public $views;

    public static function getRecent(int $limit): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');
        return $db->fetchAll(
            sql:"SELECT * FROM " . static::$table . " ORDER BY created_at DESC LIMIT ?",
            params: [$limit],
            className: static::class
        );
    }
}
