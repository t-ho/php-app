<?php

namespace App\Services;

use App\Models\UploadedImage;
use Exception;

class ImageDeletionService
{
    /**
     * Batch force delete images (for cleanup operations)
     * 
     * Logic:
     * - If file exists and deletes successfully: Delete database record
     * - If file exists but deletion fails: Keep database record (allows retry)
     * - If file doesn't exist: Delete database record (cleanup orphaned record)
     * 
     * Uses batch database operations for performance
     */
    public static function batchForceDeleteImages(array $images): array
    {
        $results = [
            'deleted' => 0,
            'failed' => 0,
            'errors' => [],
            'total_size' => 0,
            'files_missing' => 0
        ];
        
        $imageIds = [];
        
        // Step 1: Attempt to delete physical files and track which DB records can be safely deleted
        foreach ($images as $image) {
            $fullPath = static::getFullFilePath($image->file_path);
            $fileExists = file_exists($fullPath);
            $fileSize = $fileExists ? filesize($fullPath) : 0;
            
            $canDeleteDbRecord = false;
            
            if ($fileExists) {
                try {
                    if (unlink($fullPath)) {
                        $results['total_size'] += $fileSize;
                        $canDeleteDbRecord = true; // File successfully deleted
                    } else {
                        $results['errors'][] = [
                            'image' => $image->filename,
                            'error' => "Failed to delete physical file: {$fullPath}"
                        ];
                        // Don't delete DB record if file deletion failed
                    }
                } catch (Exception $e) {
                    $results['errors'][] = [
                        'image' => $image->filename,
                        'error' => $e->getMessage()
                    ];
                    // Don't delete DB record if file deletion failed
                }
            } else {
                $results['files_missing']++;
                $canDeleteDbRecord = true; // File doesn't exist, safe to delete DB record
            }
            
            // Only add to deletion list if file was successfully deleted or doesn't exist
            if ($canDeleteDbRecord) {
                $imageIds[] = $image->id;
            }
        }
        
        // Step 2: Batch delete database records only for successfully deleted or missing files
        try {
            $deletedCount = UploadedImage::batchDelete($imageIds);
            $results['deleted'] = $deletedCount;
            
        } catch (Exception $e) {
            $results['failed'] = count($imageIds);
            $results['errors'][] = [
                'type' => 'batch_delete_failed',
                'error' => $e->getMessage()
            ];
        }
        
        return $results;
    }
    
    /**
     * Get the full filesystem path for an image
     */
    private static function getFullFilePath(string $filePath): string
    {
        return __DIR__ . '/../../public' . $filePath;
    }
}