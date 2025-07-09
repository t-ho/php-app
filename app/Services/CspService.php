<?php

namespace App\Services;

/**
 * Content Security Policy Service
 * Generates and manages nonce values for CSP
 */
class CspService
{
    private static ?string $nonce = null;

    /**
     * Generate a cryptographically secure nonce value
     */
    public static function generateNonce(): string
    {
        if (self::$nonce === null) {
            self::$nonce = base64_encode(random_bytes(16));
        }

        return self::$nonce;
    }

    /**
     * Get the current nonce value
     */
    public static function getNonce(): string
    {
        return self::generateNonce();
    }

    /**
     * Generate nonce attribute for HTML elements
     */
    public static function nonceAttr(): string
    {
        return 'nonce="' . self::getNonce() . '"';
    }

    /**
     * Set CSP header with nonce support
     */
    public static function setCSPHeader(bool $isDevelopment = false): void
    {
        $nonce = self::getNonce();

        $policy = "default-src 'self'; " .
                 "script-src 'self' 'nonce-{$nonce}' " .
                             "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js " .
                             "https://cdn.jsdelivr.net/npm/chart.js " .
                             "https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/tinymce/ " .
                             ($isDevelopment ? " ws://localhost:3000" : "") . "; " .
                 "style-src 'self' 'unsafe-inline' " . // use 'unsafe-inline' for styles as tinyMCE requires it
                            "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css " .
                            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css " .
                            "https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/tinymce/; " .
                 "img-src 'self' data: blob: " .
                          "https://sp.tinymce.com " .
                          "https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/tinymce/; " .
                 "connect-src 'self' " .
                             "https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/" .
                             ($isDevelopment ? " ws://localhost:3000 wss://localhost:3000" : "") . "; " .
                 "font-src 'self' data: " .
                           "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/ " .
                           "https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/tinymce/; " .
                 "frame-src 'none'; " .
                 "object-src 'none'; " .
                 "worker-src 'self'; " .
                 "frame-ancestors 'none'; " . // Prevent framing of the site for clickjacking protection
                 "report-uri /csp-report; " . // Legacy reporting (older browsers)
                 "report-to csp-endpoint;"; // Modern reporting (newer browsers)

        header("Content-Security-Policy: {$policy}");

        // Add modern reporting endpoint definition
        header('Reporting-Endpoints: csp-endpoint="/csp-report"');
    }
}
