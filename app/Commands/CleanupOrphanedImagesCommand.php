<?php

namespace App\Commands;

use App\Models\UploadedImage;
use App\Services\ImageDeletionService;

class CleanupOrphanedImagesCommand
{
    private const LOG_FILE = 'storage/logs/image-cleanup.log';
    private const DEFAULT_DAYS_OLD = 7;
    
    public function run(array $args = []): void
    {
        $this->log("Starting orphaned image cleanup process");
        
        // Get days old from arguments or use default
        $daysOld = isset($args['days']) ? (int)$args['days'] : self::DEFAULT_DAYS_OLD;
        
        $this->log("Looking for orphaned images older than {$daysOld} days");
        
        // Get orphaned images
        $orphanedImages = UploadedImage::getOrphanedImages($daysOld);
        
        if (empty($orphanedImages)) {
            $this->log("No orphaned images found to cleanup");
            return;
        }
        
        $this->log("Found " . count($orphanedImages) . " orphaned images to cleanup");
        
        // Log details of all images to be processed
        foreach ($orphanedImages as $image) {
            $this->log("Processing image: {$image->filename} (ID: {$image->id})");
            $this->logImageDetails($image);
        }
        
        // Use batch deletion for better performance
        $this->log("Starting batch deletion of " . count($orphanedImages) . " images");
        $results = ImageDeletionService::batchForceDeleteImages($orphanedImages);
        
        // Log detailed results
        $this->log("Batch deletion completed:");
        $this->log("  - Images deleted: {$results['deleted']}");
        $this->log("  - Files missing: {$results['files_missing']}");
        $this->log("  - Total size freed: " . $this->formatFileSize($results['total_size']));
        
        if (!empty($results['errors'])) {
            $this->log("Errors encountered during batch deletion:");
            foreach ($results['errors'] as $error) {
                if (isset($error['image'])) {
                    $this->log("  - {$error['image']}: {$error['error']}", 'ERROR');
                } else {
                    $this->log("  - {$error['type']}: {$error['error']}", 'ERROR');
                }
            }
        }
        
        $this->logSummary($results['deleted'], $results['failed'], $results['total_size']);
        $this->log("Orphaned image cleanup process completed");
    }
    
    private function logImageDetails(UploadedImage $image): void
    {
        $details = [
            'ID' => $image->id,
            'Original Name' => $image->original_name,
            'File Path' => $image->file_path,
            'File Size' => $this->formatFileSize($image->file_size),
            'MIME Type' => $image->mime_type,
            'Upload Type' => $image->upload_type,
            'User ID' => $image->user_id,
            'Entity Type' => $image->entity_type ?? 'None',
            'Entity ID' => $image->entity_id ?? 'None',
            'Created At' => $image->created_at,
            'Marked for Deletion At' => $image->marked_for_deletion_at ?? 'Not marked'
        ];
        
        foreach ($details as $key => $value) {
            $this->log("  {$key}: {$value}");
        }
    }
    
    private function logSummary(int $deletedCount, int $failedCount, int $totalSize): void
    {
        $this->log("=== CLEANUP SUMMARY ===");
        $this->log("Images successfully deleted: {$deletedCount}");
        $this->log("Images failed to delete: {$failedCount}");
        $this->log("Total disk space freed: " . $this->formatFileSize($totalSize));
        $this->log("======================");
    }
    
    
    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    private function log(string $message, string $level = 'INFO'): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;
        
        // Ensure log directory exists
        $logDir = dirname(self::LOG_FILE);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Write to log file
        file_put_contents(self::LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also output to console if running in CLI
        if (php_sapi_name() === 'cli') {
            echo $logEntry;
        }
    }
}