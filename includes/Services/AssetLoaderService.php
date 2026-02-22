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
    private const USER_THEME_META_KEY = 'corbidev_theme_mode';

    private string $manifestPath;
    private string $distUri;

    public function __construct()
    {
        $this->manifestPath = get_template_directory() . '/assets/dist/.vite/manifest.json';
        $this->distUri      = get_template_directory_uri() . '/assets/dist/';
    }

    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if ($handle === 'corbidev-app') {
                return '<script type="module" src="' . esc_url($src) . '"></script>';
            }
            return $tag;
        }, 10, 3);
    }

    public function enqueue(): void
    {
        if (!file_exists($this->manifestPath)) {
            return;
        }

        $manifest = json_decode(file_get_contents($this->manifestPath), true);

        if (!is_array($manifest) || !isset($manifest['assets/src/main.js'])) {
            return;
        }

        $entry = $manifest['assets/src/main.js'];

        // JS
        if (!empty($entry['file'])) {
            wp_enqueue_script(
                'corbidev-app',
                $this->distUri . $entry['file'],
                [],
                null,
                true
            );

            wp_localize_script('corbidev-app', 'corbidevThemePreference', [
                'isLoggedIn' => is_user_logged_in(),
                'userTheme'  => $this->getCurrentUserThemePreference(),
                'restUrl'    => rest_url('corbidev/v1/theme-preference'),
                'nonce'      => wp_create_nonce('wp_rest'),
            ]);
        }

        // CSS
        if (!empty($entry['css']) && is_array($entry['css'])) {
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

    private function getCurrentUserThemePreference(): ?string
    {
        if (!is_user_logged_in()) {
            return null;
        }

        $theme = get_user_meta(get_current_user_id(), self::USER_THEME_META_KEY, true);

        return in_array($theme, ['light', 'dark'], true) ? $theme : null;
    }
}