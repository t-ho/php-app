<?php

use App\Services\Csrf;
use Core\View;

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
