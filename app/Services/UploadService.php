<?php

namespace App\Services;

use Exception;

class UploadService
{
    protected const DEFAULT_MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    protected const DEFAULT_UPLOAD_DIR = 'uploads/images';
    protected const DEFAULT_WATERMARK_TEXT = 'PHP App - tdev.app';

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

        // Apply watermark to the uploaded image
        static::addWatermark($filePath, $file['type']);

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

    /**
     * Add watermark to uploaded image using GD
     */
    public static function addWatermark(
        string $filePath,
        string $mimeType,
        string $watermarkText = self::DEFAULT_WATERMARK_TEXT
    ): void {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            error_log('GD extension not available - skipping watermark');
            return;
        }

        // Create image resource based on mime type
        $image = null;
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($filePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($filePath);
                break;
            default:
                error_log("Unsupported image type for watermarking: {$mimeType}");
                return;
        }

        if (!$image) {
            error_log("Failed to create image resource from: {$filePath}");
            return;
        }

        // Get image dimensions
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);

        // Create text color (white with transparency)
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 50);

        // Create shadow color (black with transparency)
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 70);

        // Calculate text position (bottom left with padding)
        $padding = 20;
        $font = static::getDefaultFont();
        $fontHeight = imagefontheight($font);

        $x = $padding;
        $y = $imageHeight - $padding - $fontHeight;

        // Add shadow (offset by 2 pixels)
        imagestring($image, $font, $x + 2, $y + 2, $watermarkText, $shadowColor);

        // Add main text
        imagestring($image, $font, $x, $y, $watermarkText, $textColor);

        // Save the watermarked image
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($image, $filePath, 90); // 90% quality
                break;
            case 'image/png':
                imagepng($image, $filePath, 8); // Compression level 8
                break;
            case 'image/gif':
                imagegif($image, $filePath);
                break;
            case 'image/webp':
                imagewebp($image, $filePath, 90); // 90% quality
                break;
        }

        // Clean up memory
        imagedestroy($image);
    }

    /**
     * Get default font for watermarking
     */
    private static function getDefaultFont(): int
    {
        // Use built-in font if TTF not available
        return 5; // Built-in font 5 (largest)
    }

    /**
     * Check if GD extension is available
     */
    public static function isWatermarkingAvailable(): bool
    {
        return extension_loaded('gd');
    }
}
