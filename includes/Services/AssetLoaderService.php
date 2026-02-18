<?php

namespace CorbiDev\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load Vite compiled assets using manifest.json
 */
class AssetLoaderService
{
    private string $manifestPath;
    private string $distUri;

    public function __construct()
    {
        $this->manifestPath = get_template_directory() . '/dist/.vite/manifest.json';
        $this->distUri      = get_template_directory_uri() . '/dist/';
    }

    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        if (!file_exists($this->manifestPath)) {
            return;
        }

        $manifest = json_decode(file_get_contents($this->manifestPath), true);

        if (!isset($manifest['assets/js/app.js'])) {
            return;
        }

        $entry = $manifest['assets/js/app.js'];

        // JS
        wp_enqueue_script(
            'corbidev-app',
            $this->distUri . $entry['file'],
            [],
            null,
            true
        );

        // CSS
        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $cssFile) {
                wp_enqueue_style(
                    'corbidev-style',
                    $this->distUri . $cssFile,
                    [],
                    null
                );
            }
        }
    }
}