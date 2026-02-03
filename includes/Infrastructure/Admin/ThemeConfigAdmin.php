<?php

namespace CorbiDev\Theme\Infrastructure\Admin;

use CorbiDev\Theme\Services\ThemeConfigService;
use CorbiDev\Theme\Services\ThemeConfigWriterService;

final class ThemeConfigAdmin
{
    public function __construct(
        private ThemeConfigService $reader,
        private ThemeConfigWriterService $writer
    ) {}

    public function register(): void
    {
        if (is_multisite()) {
            add_action('network_admin_menu', [$this, 'registerNetworkPage']);
        } else {
            add_action('admin_menu', [$this, 'registerSitePage']);
        }
    }

    public function registerSitePage(): void
    {
        add_theme_page(
            esc_html__('Theme configuration', 'corbidevtheme'),
            esc_html__('Theme configuration', 'corbidevtheme'),
            'manage_options',
            'corbidev-theme-config',
            [$this, 'render']
        );
    }

    public function registerNetworkPage(): void
    {
        add_menu_page(
            esc_html__('Theme configuration', 'corbidevtheme'),
            esc_html__('Theme configuration', 'corbidevtheme'),
            'manage_network_options',
            'corbidev-theme-config',
            [$this, 'render']
        );
    }

    public function render(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            check_admin_referer('corbidev_theme_config');
            $this->writer->save(['dark_mode' => isset($_POST['dark_mode'])]);
        }

        $config = $this->reader->all();
        require get_stylesheet_directory() . '/templates/admin/theme-config.php';
    }
}
