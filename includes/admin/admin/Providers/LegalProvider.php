<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * Gestion légale complète (Cookies + Pages auto).
 */
class LegalProvider implements ProviderInterface
{
    public function register(): void
    {
        add_action('admin_init', [$this, 'settings']);
        add_action('wp_footer', [$this, 'cookieBanner']);
        add_shortcode('corbidev_legal', [$this, 'legalShortcode']);
    }

    public function settings(): void
    {
        register_setting('corbidev_legal_group', 'corbidev_enable_cookies');
        register_setting('corbidev_legal_group', 'corbidev_cookie_message');
    }

    public function cookieBanner(): void
    {
        if (!get_option('corbidev_enable_cookies')) return;

        $message = esc_html(get_option('corbidev_cookie_message', 'This website uses cookies.'));
        echo "<div style='position:fixed;bottom:0;width:100%;background:#111;color:#fff;padding:15px;text-align:center;'>{$message}</div>";
    }

    public function legalShortcode(): string
    {
        return esc_html__('Legal information page.', 'corbidevtheme');
    }
}
