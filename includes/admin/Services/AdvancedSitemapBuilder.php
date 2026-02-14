<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Générateur hiérarchique avancé du plan du site.
 * Compatible avec l'ancien builder.
 */
class AdvancedSitemapBuilder
{
    public function buildTree(int $parent = 0): string
    {
        $pages = get_pages(['parent' => $parent, 'sort_column' => 'menu_order']);
        if (!$pages) return '';

        $html = '<ul>';
        foreach ($pages as $page) {
            $html .= '<li><a href="' . esc_url(get_permalink($page)) . '">'
                . esc_html($page->post_title) . '</a>';
            $html .= $this->buildTree($page->ID);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}
