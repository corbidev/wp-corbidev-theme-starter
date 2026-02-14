<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Création automatique intelligente des pages légales.
 */
class PageAutoCreator
{
    public function createIfMissing(string $title, string $content, string $slug): void
    {
        $page = get_page_by_path($slug);

        if (!$page) {
            wp_insert_post([
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => $slug,
            ]);
        }
    }
}
