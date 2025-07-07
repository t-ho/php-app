<?php

namespace App\Models;

use App\Core\App;
use App\Core\Model;

class UploadedImage extends Model
{
    protected static string $table = 'uploaded_images';

    public $id;
    public $filename;
    public $original_name;
    public $file_path;
    public $file_size;
    public $mime_type;
    public $upload_type;
    public $entity_type;
    public $entity_id;
    public $user_id;
    public $marked_for_deletion_at;
    public $created_at;
    public $updated_at;

    public static function trackUpload(
        string $filename,
        string $originalName,
        string $filePath,
        int $fileSize,
        string $mimeType,
        int $userId,
        string $uploadType = 'general',
        ?string $entityType = null,
        ?int $entityId = null
    ): ?static {
        return static::create([
            'filename' => $filename,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'upload_type' => $uploadType,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => $userId,
        ]);
    }

    public static function findByPath(string $filePath): ?self
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $result = $db->fetch(
            query: "SELECT * FROM " . static::$table . " WHERE file_path = ?",
            params: [$filePath],
            className: static::class
        );

        return $result ? $result : null;
    }

    public static function getOrphanedImages(int $daysOld = 7): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));
        $query = "SELECT * FROM " . static::$table . " 
                WHERE (marked_for_deletion_at IS NOT NULL AND marked_for_deletion_at <= ?)
                OR (created_at <= ? AND entity_id IS NULL)";

        return $db->fetchAll(
            query: $query,
            params: [$cutoffDate, $cutoffDate],
            className: static::class
        );
    }

    public static function markImagesForDeletion(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $params = array_merge([date('Y-m-d H:i:s')], $ids);

        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "UPDATE " . static::$table . " SET marked_for_deletion_at = ?
              WHERE id IN ($placeholders)";

        $db->query($query, $params);
    }

    public static function unmarkImagesForDeletion(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "UPDATE " . static::$table . "
              SET marked_for_deletion_at = NULL
              WHERE id IN ($placeholders)";

        $db->query($query, $ids);
    }

    public static function linkImagesToEntity(array $ids, int $entityId, string $entityType): void
    {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "UPDATE " . static::$table . " 
              SET entity_id = ?, entity_type = ?
              WHERE id IN ($placeholders)";

        $params = [$entityId, $entityType, ...$ids];

        $db->query($query, $params);
    }

    public static function getImagesForEntity(string $entityType, int $entityId): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $query = "SELECT * FROM " . static::$table . " 
                WHERE entity_type = ? AND entity_id = ?";

        return $db->fetchAll(
            query: $query,
            params: [$entityType, $entityId],
            className: static::class
        );
    }

    public static function extractImagePaths(string $content): array
    {
        preg_match_all('/\/uploads\/[^"\s]+/', $content, $matches);
        return array_unique($matches[0]);
    }

    public static function syncPostImages(int $postId, string $content): void
    {
        $currentImagePaths = static::extractImagePaths($content);
        $trackedImages = static::getImagesForEntity('Post', $postId);

        // Build associative array: file_path => ImageModel
        $trackedByPath = [];
        foreach ($trackedImages as $image) {
            $trackedByPath[$image->file_path] = $image;
        }

        $toMark = [];
        $toUnmark = [];
        $toLink = [];

        // Mark tracked images that are no longer in content
        foreach ($trackedByPath as $path => $image) {
            if (!in_array($path, $currentImagePaths)) {
                $toMark[] = $image->id;
            }
        }

        // Unmark images that are in content but marked for deletion
        foreach ($currentImagePaths as $path) {
            if (isset($trackedByPath[$path]) && $trackedByPath[$path]->marked_for_deletion_at) {
                $toUnmark[] = $trackedByPath[$path]->id;
            } else {
                // Check if the image exists in the database but not associated with this post
                // as when uploading via tinyMCE, the image might not be tracked yet
                $image = static::findByPath($path);
                if ($image && !$image->entity_id) {
                    $toLink[] = $image->id;
                }
            }
        }

        if (!empty($toMark)) {
            static::markImagesForDeletion($toMark);
        }

        if (!empty($toUnmark)) {
            static::unmarkImagesForDeletion($toUnmark);
        }

        if (!empty($toLink)) {
            static::linkImagesToEntity($toLink, $postId, 'Post');
        }
    }

    public function delete(): bool
    {
        if (isset($this->file_path)) {
            // Actually delete the file from filesystem
            $fullPath = __DIR__ . '/../../public' . $this->file_path;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        return parent::delete();
    }
}
