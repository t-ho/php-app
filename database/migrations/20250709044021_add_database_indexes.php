<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseMigration.php';

final class AddDatabaseIndexes extends BaseMigration
{
    /**
     * Add database indexes to improve query performance
     */
    public function up(): void
    {
        $this->runSqlFile('20250709044021_add_database_indexes_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250709044021_add_database_indexes_down.sql');
    }
}
