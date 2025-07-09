<?php

use App\Core\App;
use App\Core\View;
use App\Services\AuthorizationService;
use App\Services\CspService;
use App\Services\CsrfService;
use App\Services\ViteService;

if (!function_exists('partial')) {
    function partial(string $template, array $data = []): string
    {
        return View::partial($template, $data);
    }
}

if (!function_exists('buildPageQueryString')) {
    function buildPageQueryString(array $params, int $page): string
    {
        $params['page'] = $page;
        return http_build_query($params);
    }
}

if (!function_exists('e')) {
    function e(string $value, string $context = 'html'): string
    {
        return match ($context) {
            'html' => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            'json', 'js' => json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
            // For encoding URL path components (not full URLs or query strings!)
            'url'  => rawurlencode($value),
            default => throw new InvalidArgumentException("Invalid context"),
        };
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        $token = CsrfService::getToken();
        $tokenName = CsrfService::CSRF_TOKEN_NAME;
        return <<<TAG
        <input type="hidden" name="{$tokenName}" value="{$token}" />
        TAG;
    }
}

if (!function_exists('csrf_token_value')) {
    function csrf_token_value(): string
    {
        return CsrfService::getToken();
    }
}

if (!function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return CspService::getNonce();
    }
}

if (!function_exists('isAuthorizedFor')) {
    function isAuthorizedFor(string $action, mixed $resource = null): bool
    {
        return AuthorizationService::isAuthorizedFor($action, $resource);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        try {
            $config = App::get('config');

            $keys = explode('.', $key);
            $value = $config;

            foreach ($keys as $k) {
                if (!is_array($value) || !array_key_exists($k, $value)) {
                    return $default;
                }
                $value = $value[$k];
            }

            return $value;
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('sanitizeHtml')) {
    function sanitizeHtml(string $html): string
    {
        static $purifier = null;

        if ($purifier === null) {
            $config = \HTMLPurifier_Config::createDefault();

            // Set a writable cache directory or disable caching
            $cacheDir = __DIR__ . '/../../storage/cache/htmlpurifier';
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }

            if (is_writable($cacheDir)) {
                $config->set('Cache.SerializerPath', $cacheDir);
            } else {
                // Disable caching if we can't write to cache directory
                $config->set('Cache.DefinitionImpl', null);
            }

            // Allow common blog formatting + images + some styling
            $config->set('HTML.Allowed', implode(',', [
                'p', 'br', 'hr', 'b', 'i', 'strong', 'em', 'u',
                'ul', 'ol', 'li',
                'a[href|target|rel]',
                'img[src|alt|width|height]',
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'blockquote', 'pre', 'code',
                'span[style]', 'div[style]',
                'table', 'thead', 'tbody', 'tr', 'td', 'th'
            ]));

            // Allow some inline styles (TinyMCE uses them for alignment, color, etc.)
            $config->set('CSS.AllowedProperties', [
                'text-align', 'color', 'background-color',
                'font-weight', 'font-style', 'text-decoration',
                'padding-left', 'margin', 'border',
            ]);

            // Restrict URI schemes to safe ones
            $config->set('URI.AllowedSchemes', [
                'http' => true,
                'https' => true,
                'data' => true, // Allow base64 images from TinyMCE
            ]);

            // Disable dangerous schemes
            $config->set('URI.DisableExternal', false); // Allow external links
            $config->set('URI.DisableResources', false); // Allow images

            // Remove empty elements
            $config->set('AutoFormat.RemoveEmpty', true);

            $purifier = new \HTMLPurifier($config);
        }

        return $purifier->purify($html);
    }
}

if (!function_exists('generateExcerpt')) {
    function generateExcerpt(string $html, int $length = 150, bool $addEllipsis = true): string
    {
        // Remove HTML tags
        $text = strip_tags($html);

        // Decode HTML entities (like &nbsp;, &amp;, etc.)
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Replace multiple whitespace with single space
        $text = preg_replace('/\s+/', ' ', $text);

        // Trim whitespace
        $text = trim($text);

        // Check if truncation is needed
        $wasTruncated = false;
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length);
            // Find the last space to avoid cutting words
            $lastSpace = mb_strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = mb_substr($text, 0, $lastSpace);
            }
            $wasTruncated = true;
        }

        // Add ellipsis only if content was actually truncated and ellipsis is requested
        if ($wasTruncated && $addEllipsis) {
            $text .= '...';
        }

        return $text;
    }
}

if (!function_exists('getCurrentPath')) {
    function getCurrentPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}

if (!function_exists('isActiveNavItem')) {
    function isActiveNavItem(string $path): bool
    {
        $currentPath = getCurrentPath();

        // Exact match
        if ($currentPath === $path) {
            return true;
        }

        // For paths like /admin/dashboard, also match /admin/dashboard/*
        if ($path !== '/' && str_starts_with($currentPath, $path)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('asset_tags')) {
    function asset_tags(string $entry): string
    {
        return ViteService::instance()->renderTags($entry);
    }
}
