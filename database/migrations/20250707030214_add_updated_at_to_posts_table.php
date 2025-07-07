<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseMigration.php';

final class AddUpdatedAtToPostsTable extends BaseMigration
{
    /**
     * Add updated_at column to posts table
     */
    public function up(): void
    {
        $this->runSqlFile('20250707030214_add_updated_at_to_posts_table_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250707030214_add_updated_at_to_posts_table_down.sql');
    }
}
