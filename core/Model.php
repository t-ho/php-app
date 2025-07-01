<?php

declare(strict_types=1);

namespace Core;

abstract class Model
{
    protected static string $table;

    public static function all(): array
    {
        /** @var Database $db */
        $db = App::get('database');

        return $db->fetchAll(
            sql: "SELECT * FROM " . static::$table,
            className: static::class
        );
    }

    public static function find(mixed $id): static|null
    {
        /** @var Database $db */
        $db = App::get('database');
        return $db->fetch(
            sql: "SELECT * FROM " . static::$table . " WHERE id = ?",
            params: [$id],
            className: static::class
        );
    }

    public static function create(array $data): static
    {
        /** @var Database $db */
        $db = App::get('database');
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";

        $db->query($sql, array_values($data));

        return static::find($db->lastInsertId());
    }
}
