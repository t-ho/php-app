<?php

use App\Services\Authorization;
use App\Services\Csrf;
use App\Core\App;
use App\Core\View;

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
        $token = Csrf::getToken();
        $tokenName = Csrf::CSRF_TOKEN_NAME;
        return <<<TAG
        <input type="hidden" name="{$tokenName}" value="{$token}" />
        TAG;
    }
}

if (!function_exists('isAuthorizedFor')) {
    function isAuthorizedFor(string $action, mixed $resource = null): bool
    {
        return Authorization::isAuthorizedFor($action, $resource);
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
