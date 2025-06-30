<?php

declare(strict_types=1);

namespace Core;

use PDO;

abstract class Model
{
    protected static $table;

    public static function all(): array
    {
        $db = App::get('database');
        $results = $db->query("SELECT * FROM " . static::$table)
          ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            [static::class, 'createFromArray'],
            $results
        );
    }

    public function find(mixed $id): static|null
    {
        $db = App::get('database');
        $results = $db->query("SELECT * FROM " . static::$table . " WHERE id = ?", [$id])
          ->fetch(PDO::FETCH_ASSOC);

        return $results ? static::createFromArray($results) : null;
    }

    public function create(array $data): static
    {
        $db = App::get('database');
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";

        $db->query($sql, array_values($data));

        return static::find($db->lastInsertId());
    }

    protected static function createFromArray(array $data): static
    {
        $instance = new static();
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }
        return $instance;
    }
}
