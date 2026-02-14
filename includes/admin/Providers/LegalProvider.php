<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\LegalPageManager;

/**
 * Gestion légale complète (Terms, Privacy, Cookies).
 */
class LegalProvider implements ProviderInterface
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function register(): void
    {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_post_corbidev_generate_legal', [$this, 'generatePages']);
    }

    public function registerSettings(): void
    {
        register_setting('corbidev_legal_group', 'corbidev_enable_legal');
        register_setting('corbidev_legal_group', 'corbidev_enable_cookies');
    }

    public function generatePages(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $manager = new LegalPageManager();
        $manager->createDefaultPages();

        wp_redirect(admin_url('themes.php?page=corbidev-theme-settings'));
        exit;
    }
}
