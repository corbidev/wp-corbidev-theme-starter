<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * Gestion légale complète (Cookies + Pages).
 */
class LegalProvider implements ProviderInterface
{
    public function __construct(private $repository){}

    public function register(): void
    {
        add_action('wp_footer', [$this,'cookieBanner']);
    }

    public function cookieBanner(): void
    {
        echo '<div style="display:none" id="corbidev-cookie-banner">';
        echo esc_html__('This website uses cookies.', 'corbidevtheme');
        echo '</div>';
    }
}
