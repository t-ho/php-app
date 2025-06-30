<?php

declare(strict_types=1);

namespace Core;

class Database
{
    protected \PDO $pdo;

    public function __construct(array $config)
    {
        $this->connect($config);
    }

    protected function connect(array $config): void
    {
        try {
            $dsn = $this->createDsn($config);
            $username = $config['username'] ?? null;
            $password = $config['password'] ?? null;
            $options = $config['options'] ?? null;

            $this->pdo = new \PDO(
                dsn: $dsn,
                username: $username,
                password: $password,
                options: $options
            );

            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database connection error: " . $e->getMessage());
        }
    }

    protected function createDsn(array $config): string
    {
        $driver = $config['driver'];
        $dbname = $config['dbname'];

        return match ($driver) {
            'sqlite' => "sqlite:{$dbname}",
            default => throw new \Exception("Unsupported database driver: {$driver}"),
        };
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("Failed to prepare SQL statement: {$sql}");
        }

        if (!$stmt->execute($params)) {
            throw new \RuntimeException("Failed to execute SQL statement: {$sql}");
        }

        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function fetch(string $sql, array $params = []): object|false
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(\PDO::FETCH_OBJ) ?: null;
    }

    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }
}
