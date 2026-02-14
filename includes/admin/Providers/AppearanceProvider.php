<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

class AppearanceProvider implements ProviderInterface
{
    public function __construct($repository){}

    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_enqueue_scripts', [$this, 'assets']);
    }

    public function menu(): void
    {
        if (!CapabilityGuard::canManage()) return;

        add_theme_page(
            esc_html__('Theme Settings', 'corbidevtheme'),
            esc_html__('CorbiDev Settings', 'corbidevtheme'),
            'manage_options',
            'corbidev-theme-settings',
            [$this, 'render']
        );
    }

    public function assets(): void
    {
        wp_enqueue_script(
            'corbidev-admin-ui',
            get_template_directory_uri() . '/includes/admin/Assets/js/admin-ui.js',
            [],
            '1.0.0',
            true
        );

        wp_enqueue_style(
            'corbidev-admin-ui',
            get_template_directory_uri() . '/includes/admin/Assets/css/admin-ui.css',
            [],
            '1.0.0'
        );

        wp_localize_script('corbidev-admin-ui', 'CorbiDevAdmin', [
            'restUrl' => esc_url_raw(rest_url('corbidev/v1/options')),
            'nonce'   => wp_create_nonce('wp_rest')
        ]);
    }

    public function render(): void
    {
        ?>
        <div class="wrap corbidev-admin">
            <h1><?php echo esc_html__('Theme Settings', 'corbidevtheme'); ?></h1>

            <div class="corbidev-tabs">
                <button type="button" data-tab="brand" class="active"><?php echo esc_html__('Brand', 'corbidevtheme'); ?></button>
                <button type="button" data-tab="legal"><?php echo esc_html__('Legal', 'corbidevtheme'); ?></button>
                <button type="button" data-tab="sitemap"><?php echo esc_html__('Sitemap', 'corbidevtheme'); ?></button>
            </div>

            <form id="corbidev-admin-form">

                <div class="corbidev-tab-content active" data-content="brand">
                    <label><?php echo esc_html__('Primary Color', 'corbidevtheme'); ?></label>
                    <input type="text" name="corbidev_primary_color" value="<?php echo esc_attr(get_option('corbidev_primary_color')); ?>" />
                </div>

                <div class="corbidev-tab-content" data-content="legal">
                    <label>
                        <input type="checkbox" name="corbidev_enable_cookies" <?php checked(get_option('corbidev_enable_cookies'), 1); ?> />
                        <?php echo esc_html__('Enable Cookies Banner', 'corbidevtheme'); ?>
                    </label>
                </div>

                <div class="corbidev-tab-content" data-content="sitemap">
                    <p><?php echo esc_html__('Sitemap generated automatically.', 'corbidevtheme'); ?></p>
                </div>

                <div class="corbidev-actions">
                    <button type="button" id="corbidev-save" class="button button-primary">
                        <?php echo esc_html__('Save', 'corbidevtheme'); ?>
                    </button>
                    <button type="button" id="corbidev-cancel" class="button">
                        <?php echo esc_html__('Cancel', 'corbidevtheme'); ?>
                    </button>
                </div>

            </form>
        </div>
        <?php
    }
}
