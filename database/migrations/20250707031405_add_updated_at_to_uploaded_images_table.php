<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseMigration.php';

final class AddUpdatedAtToUploadedImagesTable extends BaseMigration
{
    /**
     * Add updated_at column to uploaded_images table
     */
    public function up(): void
    {
        $this->runSqlFile('20250707031405_add_updated_at_to_uploaded_images_table_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250707031405_add_updated_at_to_uploaded_images_table_down.sql');
    }
}
