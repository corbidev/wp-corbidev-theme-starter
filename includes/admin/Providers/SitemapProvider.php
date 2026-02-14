
<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SitemapBuilder;

class SitemapProvider implements ProviderInterface
{
    public function __construct(private $repository) {}

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
