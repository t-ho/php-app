<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

class Database
{
    protected PDO $pdo;

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

            $this->pdo = new PDO(
                dsn: $dsn,
                username: $username,
                password: $password,
                options: $options
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection error: " . $e->getMessage());
        }
    }

    protected function createDsn(array $config): string
    {
        $driver = $config['driver'];
        $dbname = $config['dbname'];

        return match ($driver) {
            'sqlite' => "sqlite:{$dbname}",
            default => throw new Exception("Unsupported database driver: {$driver}"),
        };
    }

    public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare SQL statement: {$query}");
        }

        if (!$stmt->execute($params)) {
            throw new RuntimeException("Failed to execute SQL statement: {$query}");
        }

        return $stmt;
    }

    public function fetchAll(string $query, array $params = [], ?string $className = null): array
    {
        $stmt = $this->query($query, $params);

        return $className
          ? $stmt->fetchAll(PDO::FETCH_CLASS, $className)
          : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch(string $query, array $params = [], ?string $className = null): mixed
    {
        $stmt = $this->query($query, $params);
        $stmt->setFetchMode($className ? PDO::FETCH_CLASS : PDO::FETCH_ASSOC, $className);

        return $stmt->fetch();
    }

    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }
}
