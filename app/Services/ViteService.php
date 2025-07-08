<?php

namespace App\Services;

use App\Core\App;

class ViteService
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
            return $this->getDevAssets($entry);
        }

        return $this->getProdAssets($entry);
    }

    private function getDevAssets(string $entry): array
    {
        $devServer = 'http://localhost:3000';
        
        return [
            'js' => ["{$devServer}/{$entry}"],
            'css' => []
        ];
    }

    private function getProdAssets(string $entry): array
    {
        $manifest = $this->getManifest();
        
        if (!isset($manifest[$entry])) {
            return ['js' => [], 'css' => []];
        }
        
        $manifestEntry = $manifest[$entry];
        $assets = ['js' => [], 'css' => []];

        // Main entry file
        if (isset($manifestEntry['file'])) {
            $assets['js'][] = '/dist/' . $manifestEntry['file'];
        }

        // CSS files
        if (isset($manifestEntry['css'])) {
            foreach ($manifestEntry['css'] as $css) {
                $assets['css'][] = '/dist/' . $css;
            }
        }

        return $assets;
    }

    public function renderTags(string $entry): string
    {
        $assets = $this->asset($entry);
        $html = '';
        $nonce = csp_nonce();

        // Include Vite client for HMR in development
        if ($this->isDev()) {
            // Always use localhost for browser-side HMR connection
            $devServer = 'http://localhost:3000';
            $html .= '<script type="module" nonce="' . $nonce .
                     '" src="' . $devServer . '/@vite/client"></script>' . "\n";
        }

        // CSS files
        foreach ($assets['css'] as $css) {
            $html .= "<link rel=\"stylesheet\" href=\"{$css}\">\n";
        }

        // JavaScript files
        foreach ($assets['js'] as $js) {
            $html .= "<script type=\"module\" nonce=\"{$nonce}\" src=\"{$js}\"></script>\n";
        }

        return $html;
    }

    // Get service instance from container
    public static function instance(): self
    {
        return App::get('vite');
    }
}
