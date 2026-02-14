<?php
namespace CorbiDev\Theme\Admin\Services;

class SitemapAdvanced
{
    public function build(int $parent = 0): string
    {
        $pages = get_pages(['parent' => $parent, 'sort_column' => 'menu_order']);
        if (!$pages) return '';

        $html = '<ul>';
        foreach ($pages as $page) {
            $html .= '<li><a href="' . esc_url(get_permalink($page)) . '">'
                . esc_html($page->post_title) . '</a>';
            $html .= $this->build($page->ID);
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
