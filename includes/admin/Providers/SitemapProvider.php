<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SitemapBuilder;

/**
 * Sitemap complet hiérarchique.
 */
class SitemapProvider implements ProviderInterface
{
    public function __construct($repository) {}

    public function register(): void
    {
        add_shortcode('corbidev_sitemap', [$this, 'render']);
    }

    public function render(): string
    {
        $builder = new SitemapBuilder();
        return $builder->build();
    }
}
