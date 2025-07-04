<?php

namespace App\Services;

use Exception;

class UploadService
{
    private const DEFAULT_MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    private const DEFAULT_UPLOAD_DIR = 'uploads/images';

    public static function uploadImage(
        array $file,
        string $uploadDir = self::DEFAULT_UPLOAD_DIR,
        int $maxFileSize = self::DEFAULT_MAX_FILE_SIZE
    ): string {
        static::validateImageFile($file, $maxFileSize);

        $fullUploadDir = static::getFullUploadPath($uploadDir);
        static::ensureDirectoryExists($fullUploadDir);

        $filename = static::generateUniqueFilename($file['name']);
        $filePath = $fullUploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Failed to save file');
        }

        return '/' . $uploadDir . '/' . $filename;
    }

    public static function validateImageFile(array $file, int $maxFileSize = self::DEFAULT_MAX_FILE_SIZE): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload failed');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
        }

        if ($file['size'] > $maxFileSize) {
            $maxSizeMB = round($maxFileSize / (1024 * 1024), 1);
            throw new Exception("File too large. Maximum size is {$maxSizeMB}MB.");
        }
    }

    public static function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }
    }

    public static function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $extension;
    }

    private static function getFullUploadPath(string $uploadDir): string
    {
        return __DIR__ . '/../../public/' . $uploadDir . '/';
    }
}
