<?php

namespace App\Helpers;

class ViteHelper
{
    private $manifest = null;
    private $isDev = null;

    public function isDev(): bool
    {
        if ($this->isDev === null) {
            // Check environment variable
            $this->isDev = ($_ENV['APP_ENV'] ?? 'production') === 'development';
            
            // If no env var, check if manifest exists
            if (!isset($_ENV['APP_ENV'])) {
                $manifestPath = $_SERVER['DOCUMENT_ROOT'] . '/../public/dist/.vite/manifest.json';
                $this->isDev = !file_exists($manifestPath);
            }
        }
        return $this->isDev;
    }

    public function getManifest(): array
    {
        if ($this->manifest === null) {
            $manifestPath = $_SERVER['DOCUMENT_ROOT'] . '/../public/dist/.vite/manifest.json';
            if (file_exists($manifestPath)) {
                $this->manifest = json_decode(file_get_contents($manifestPath), true) ?: [];
            } else {
                $this->manifest = [];
            }
        }
        return $this->manifest;
    }

    public function asset(string $entry): array
    {
        if ($this->isDev()) {
            // Development mode: serve from Vite dev server
            // Always use localhost for browser-side connections
            $devServer = 'http://localhost:3000';
            return [
                'js' => ["{$devServer}/{$entry}"],
                'css' => []
            ];
        }

        // Production mode: use manifest
        $manifest = $this->getManifest();
        if (!isset($manifest[$entry])) {
            return ['js' => [], 'css' => []];
        }

        $assets = ['js' => [], 'css' => []];
        
        // Main entry file
        if (isset($manifest[$entry]['file'])) {
            $assets['js'][] = '/dist/' . $manifest[$entry]['file'];
        }

        // CSS files
        if (isset($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $css) {
                $assets['css'][] = '/dist/' . $css;
            }
        }

        // Import dependencies
        if (isset($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $import) {
                if (isset($manifest[$import]['file'])) {
                    $assets['js'][] = '/dist/' . $manifest[$import]['file'];
                }
                if (isset($manifest[$import]['css'])) {
                    foreach ($manifest[$import]['css'] as $css) {
                        $assets['css'][] = '/dist/' . $css;
                    }
                }
            }
        }

        return $assets;
    }

    public function renderTags(string $entry): string
    {
        $assets = $this->asset($entry);
        $html = '';

        // Include Vite client for HMR in development
        if ($this->isDev()) {
            // Always use localhost for browser-side HMR connection
            $devServer = 'http://localhost:3000';
            $html .= '<script type="module" src="' . $devServer . '/@vite/client"></script>' . "\n";
        }

        // CSS files
        foreach ($assets['css'] as $css) {
            $html .= "<link rel=\"stylesheet\" href=\"{$css}\">\n";
        }

        // JavaScript files
        foreach ($assets['js'] as $js) {
            $html .= "<script type=\"module\" src=\"{$js}\"></script>\n";
        }

        return $html;
    }

    // Helper method to get instance from container
    public static function instance(): self
    {
        return \App\Core\App::get('vite');
    }
}