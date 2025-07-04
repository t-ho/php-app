<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected static string $table;

    public $id;

    public static function getTable(): string
    {
        return static::$table;
    }

    public static function all(): array
    {
        /** @var Database $db */
        $db = App::get('database');

        return $db->fetchAll(
            query: "SELECT * FROM " . static::$table,
            className: static::class
        );
    }

    public static function find(mixed $id): static|false
    {
        /** @var Database $db */
        $db = App::get('database');
        return $db->fetch(
            query: "SELECT * FROM " . static::$table . " WHERE id = ?",
            params: [$id],
            className: static::class
        );
    }

    public static function findOrFail(mixed $id): static
    {
        $model = static::find($id);
        if ($model === false) {
            throw new \RuntimeException("Model not found with ID: {$id}");
        }
        return $model;
    }

    public static function create(array $data): static
    {
        /** @var Database $db */
        $db = App::get('database');
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";

        $db->query($query, array_values($data));

        return static::find($db->lastInsertId());
    }

    public function save(): static
    {
        /** @var Database $db */
        $db = App::get('database');
        $data = get_object_vars($this);

        // Only keep fillable fields if defined
        if (property_exists(static::class, 'fillable')) {
            $fillable = static::$fillable ?? [];
            $data = array_intersect_key($data, array_flip($fillable));
        }

        if (!isset($this->id)) {
            unset($data['id']); // Remove id if it exists, as it will be auto-generated
            return static::create($data);
        }

        unset($data['id']); // Remove id for update
        $columns = array_map(fn ($column) => "{$column} = ?", array_keys($data));
        $query = "UPDATE " . static::$table . " SET " . implode(', ', $columns) . " WHERE id = ?";
        $params = array_values($data);
        $params[] = $this->id;

        $db->query($query, $params);

        return $this;
    }

    public static function getRecent(?int $limit = null, ?int $page = null): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "SELECT * FROM " . static::$table;
        $params = [];

        $query .= " ORDER BY created_at DESC";

        if ($limit !== null) {
            $query .= " LIMIT " . (int)$limit;
        }

        if ($page !== null && $limit !== null) {
            $offset = ($page - 1) * $limit;
            $query .= " OFFSET ?";
            $params[] = $offset;
        }

        return $db->fetchAll(
            query: $query,
            params: $params,
            className: static::class
        );
    }

    public static function count(): int
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "SELECT COUNT(*) FROM " . static::$table;

        return (int) $db->query(
            query: $query,
        )->fetchColumn();
    }

    public function delete(): bool
    {
        /** @var Database $db */
        $db = App::get('database');

        if (!isset($this->id)) {
            return false; // Cannot delete without an ID
        }

        $query = "DELETE FROM " . static::$table . " WHERE id = ?";
        $result = $db->query($query, [$this->id]);

        return $result > 0; // Return true if rows were affected
    }
}
