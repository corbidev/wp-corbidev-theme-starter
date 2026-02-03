<?php
namespace CorbiDev\Theme\Core;

final class Assets
{
    private const DEV = 'http://localhost:5173';

    public static function register(): void
    {
        add_action('wp_enqueue_scripts', [self::class, 'front']);
        add_action('admin_enqueue_scripts', [self::class, 'admin']);
    }

    private static function isHttp(): bool
    {
        return php_sapi_name() !== 'cli'
            && !defined('WP_CLI')
            && !defined('DOING_CRON');
    }

    private static function isDev(): bool
    {
        return self::isHttp() && defined('WP_DEBUG') && WP_DEBUG === true;
    }

    public static function front(): void
    {
        self::enqueue('front');
    }

    public static function admin(): void
    {
        self::enqueue('admin');
    }

    private static function enqueue(string $entry): void
    {
        if (self::isDev()) {
            wp_enqueue_script('vite-client', self::DEV . '/@vite/client', [], null, true);
            wp_enqueue_script('vite-' . $entry, self::DEV . '/vite/' . $entry . '.js', [], null, true);
            return;
        }

        $dist = get_template_directory() . '/assets/dist';
        $uri  = get_template_directory_uri() . '/assets/dist';
        $manifest = $dist . '/manifest.json';

        if (!file_exists($manifest)) return;

        $data = json_decode(file_get_contents($manifest), true);
        if (!isset($data[$entry . '.js'])) return;

        $asset = $data[$entry . '.js'];

        if (!empty($asset['css'])) {
            foreach ($asset['css'] as $css) {
                wp_enqueue_style('corbidev-' . $entry, $uri . '/' . $css, [], null);
            }
        }

        wp_enqueue_script('corbidev-' . $entry, $uri . '/' . $asset['file'], [], null, true);
    }
}
