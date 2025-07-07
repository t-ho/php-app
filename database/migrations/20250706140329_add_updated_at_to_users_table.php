<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseMigration.php';

final class AddUpdatedAtToUsersTable extends BaseMigration
{
    /**
     * Add updated_at column to users table
     */
    public function up(): void
    {
        $this->runSqlFile('20250706140329_add_updated_at_to_users_table_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250706140329_add_updated_at_to_users_table_down.sql');
    }
}

