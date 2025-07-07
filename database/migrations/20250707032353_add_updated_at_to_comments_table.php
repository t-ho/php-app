<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseMigration.php';

final class AddUpdatedAtToCommentsTable extends BaseMigration
{
    /**
     * Add updated_at column to comments table
     */
    public function up(): void
    {
        $this->runSqlFile('20250707032353_add_updated_at_to_comments_table_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250707032353_add_updated_at_to_comments_table_down.sql');
    }
}
