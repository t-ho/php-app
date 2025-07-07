<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseMigration.php';

final class RenameContentToSanitizedHtmlContent extends BaseMigration
{
    /**
     * Rename content column to sanitized_html_content in posts table
     */
    public function up(): void
    {
        $this->runSqlFile('20250707040354_rename_content_to_sanitized_html_content_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250707040354_rename_content_to_sanitized_html_content_down.sql');
    }
}
