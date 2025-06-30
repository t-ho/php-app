<?php

namespace Core;

class View
{
    public static function render(string $template, array $data = [], ?string $layout = null): string
    {
        $content = self::renderTemplate($template, $data);

        return self::renderLayout($layout, $data, $content);
    }

    protected static function renderTemplate(string $template, array $data): string
    {
        extract($data);
        $path = dirname(__DIR__) . "/app/Views/{$template}.php";

        if (!file_exists($path)) {
            throw new \RuntimeException("Error: Template file not found: {$template} ({$path})");
        }

        ob_start();
        require $path;
        return ob_get_clean();
    }

    protected static function renderLayout(?string $template, array $data, string $content): string
    {
        if ($template === null) {
            return $content;
        }

        extract([...$data, 'content' => $content]);
        $path = dirname(__DIR__) . "/app/Views/{$template}.php";

        if (!file_exists($path)) {
            throw new \RuntimeException("Error: Layout file not found: {$template} ({$path})");
        }

        ob_start();
        require $path;
        return ob_get_clean();
    }
}
