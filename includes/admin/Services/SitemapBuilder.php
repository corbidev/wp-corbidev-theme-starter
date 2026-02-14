
<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Générateur hiérarchique du plan du site.
 */
class SitemapBuilder
{
    public function build(): string
    {
        $pages = get_pages(['sort_column' => 'menu_order']);
        $html = '<ul>';

        foreach ($pages as $page) {
            $html .= '<li><a href="' . esc_url(get_permalink($page)) . '">'
                . esc_html($page->post_title) . '</a></li>';
        }

        $html .= '</ul>';
        return $html;
    }
}
