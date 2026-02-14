<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Service de création intelligente des pages légales.
 */
class LegalPageManager
{
    public function createDefaultPages(): void
    {
        $this->createIfMissing(
            'Terms of Use',
            'Default Terms of Use content.',
            'terms-of-use'
        );

        $this->createIfMissing(
            'Privacy Policy',
            'Default Privacy Policy content.',
            'privacy-policy'
        );

        $this->createIfMissing(
            'Cookie Policy',
            'Default Cookie Policy content.',
            'cookie-policy'
        );
    }

    private function createIfMissing(string $title, string $content, string $slug): void
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
