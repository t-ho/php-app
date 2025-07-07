<?php

namespace App\Models;

use App\Core\App;
use App\Core\Model;

class Post extends Model
{
    protected static string $table = 'posts';

    public $id;
    public $title;
    public $sanitized_html_content;
    public $created_at;
    public $updated_at;
    public $user_id;
    public $views;
    public $user_name;

    protected static array $fillable = ['title', 'sanitized_html_content', 'user_id', 'views'];

    public static function getRecent(?int $limit = null, ?int $page = null, ?string $search = null): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "SELECT p.*, u.name as user_name FROM " . static::$table . " p 
                  JOIN " . User::getTable() . " u ON p.user_id = u.id";
        $params = [];

        if ($search !== null) {
            $query .= " WHERE p.title LIKE ? OR p.sanitized_html_content LIKE ? OR u.name LIKE ?";
            $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
        }

        $query .= " ORDER BY p.created_at DESC";

        if ($limit !== null) {
            $query .= " LIMIT " . (int)$limit;
        }

        if ($page !== null && $limit !== null) {
            $offset = ($page - 1) * $limit;
            $query .= " OFFSET " . (int)$offset;
        }

        return $db->fetchAll(
            query: $query,
            params: $params,
            className: static::class
        );
    }

    public static function count(?string $search = null): int
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "SELECT COUNT(*) FROM " . static::$table;
        $params = [];

        if ($search !== null) {
            $query .= " WHERE title LIKE ? OR sanitized_html_content LIKE ?";
            $params = ["%{$search}%", "%{$search}%"];
        }

        return (int) $db->query(
            query: $query,
            params: $params,
        )->fetchColumn();
    }

    public static function incrementViews(int $id): void
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $db->query(
            query: "UPDATE " . static::$table . " SET views = views + 1 WHERE id = ?",
            params: [$id]
        );
    }
}
