<?php

namespace App\Services;

use App\Models\UploadedImage;
use App\Services\Auth;

class ImageUploadService extends UploadService
{
    public static function uploadAndTrack(
        array $file,
        string $uploadType = 'general',
        ?string $entityType = null,
        ?int $entityId = null,
        string $uploadDir = self::DEFAULT_UPLOAD_DIR,
        int $maxFileSize = self::DEFAULT_MAX_FILE_SIZE
    ): string {
        // Handle file upload using parent method
        $imageUrl = parent::uploadImage($file, $uploadDir, $maxFileSize);

        $user = Auth::user();

        error_log('Image upload by user: ' . ($user ? $user->id : 'guest'));

            UploadedImage::trackUpload(
                filename: basename($imageUrl),
                originalName: $file['name'],
                filePath: $imageUrl,
                fileSize: $file['size'],
                mimeType: $file['type'],
                uploadType: $uploadType,
                userId: $user->id,
                entityType: $entityType,
                entityId: $entityId
            );

        return $imageUrl;
    }

    public static function uploadPostImage(array $file): string
    {
        return static::uploadAndTrack(
            file: $file,
            uploadType: 'post'
        );
    }
}