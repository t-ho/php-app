<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Base Migration Class
 *
 * Provides helper methods for loading external SQL files from migration directory
 */
abstract class BaseMigration extends AbstractMigration
{
    /**
     * Get the migration directory path
     */
    protected function getMigrationDir(): string
    {
        $reflection = new \ReflectionClass($this);
        return dirname($reflection->getFileName());
    }

    /**
     * Load SQL from external file in sql directory
     */
    protected function loadSql(string $filename): string
    {
        $migrationDir = $this->getMigrationDir();
        $filePath = $migrationDir . '/sql/' . $filename;

        if (!file_exists($filePath)) {
            throw new \RuntimeException("SQL file not found: {$filePath}");
        }

        $sql = file_get_contents($filePath);
        if ($sql === false) {
            throw new \RuntimeException("Failed to read SQL file: {$filePath}");
        }

        return trim($sql);
    }

    /**
     * Execute SQL from external file in migration directory
     */
    protected function runSqlFile(string $filename): void
    {
        $sql = $this->loadSql($filename);

        // Split multiple statements and execute each one
        $statements = array_filter(
            array_map('trim', preg_split('/;\s*$/m', $sql)),
            fn ($stmt) => !empty($stmt)
        );

        foreach ($statements as $statement) {
            $this->execute($statement);
        }
    }
}