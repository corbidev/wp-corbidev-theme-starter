<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * Sitemap hiérarchique complet.
 */
class EnterpriseSitemapProvider implements ProviderInterface
{
    public function __construct(private $repository){}

    public function register(): void
    {
        add_shortcode('corbidev_sitemap', [$this, 'render']);
    }

    public function render(): string
    {
        $pages = get_pages(['sort_column'=>'menu_order']);
        $html = '<ul>';
        foreach($pages as $page){
            $html .= '<li><a href="'.esc_url(get_permalink($page)).'">'.esc_html($page->post_title).'</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
