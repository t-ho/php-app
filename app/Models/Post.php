<?php

namespace App\Models;

use App\Core\App;
use App\Core\Model;

class Post extends Model
{
    protected static string $table = 'posts';

    public const DEFAULT_TOP_VIEWED_POSTS_LIMIT = 5;

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
            query: "UPDATE " . static::$table . " SET views = views + 1, updated_at = updated_at WHERE id = ?",
            params: [$id]
        );
    }

    public static function getTotalViews(): int
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        return (int) $db->query(
            query: "SELECT SUM(views) FROM " . static::$table,
            params: []
        )->fetchColumn();
    }

    /**
     * Get top viewed posts optimized for chart rendering
     *
     * This method is specifically optimized for chart performance by:
     * - Selecting only required columns (id, title, views) instead of p.*
     * - Avoiding encoding/escaping of sanitized_html_content which is not needed for charts
     * - Reducing memory usage and JSON payload size
     *
     * @param int $limit Maximum number of posts to return
     * @return array Array of post objects with id, title, views properties
     */
    public static function getTopViewedPostsForChart(int $limit = self::DEFAULT_TOP_VIEWED_POSTS_LIMIT): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        return $db->fetchAll(
            query: "SELECT p.id, p.title, p.views FROM " . static::$table . " p 
                    ORDER BY p.views DESC LIMIT " . (int)$limit,
            params: [],
            className: static::class
        );
    }
}
