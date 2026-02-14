<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * Gestion légale avancée (cookies + pages auto-créées).
 */
class EnterpriseLegalProvider implements ProviderInterface
{
    public function __construct(private $repository){}

    public function register(): void
    {
        add_action('wp_footer', [$this, 'cookieBanner']);
    }

    public function cookieBanner(): void
    {
        echo '<div id="corbidev-cookie-banner" style="display:none;">';
        echo esc_html__('This website uses cookies.', 'corbidevtheme');
        echo '</div>';
    }
}
