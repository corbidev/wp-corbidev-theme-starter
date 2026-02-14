
<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\PageAutoCreator;

class LegalProvider implements ProviderInterface
{
    public function __construct(private $repository) {}

    public function register(): void
    {
        add_action('init', [$this, 'createPages']);
        add_action('wp_footer', [$this, 'cookieBanner']);
    }

    public function createPages(): void
    {
        $creator = new PageAutoCreator();

        $creator->createIfMissing(
            esc_html__('Terms of Use', 'corbidevtheme'),
            esc_html__('Default terms content.', 'corbidevtheme'),
            'terms-of-use'
        );
    }

    public function cookieBanner(): void
    {
        echo '<div id="corbidev-cookie-banner" style="display:none;">';
        echo esc_html__('This website uses cookies.', 'corbidevtheme');
        echo '</div>';
    }
}
