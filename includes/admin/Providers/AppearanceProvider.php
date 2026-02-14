<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

/**
 * Gestion Apparence (Admin + Customizer).
 */
class AppearanceProvider implements ProviderInterface
{
    private SettingsRepository $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'settings']);
        add_action('customize_register', [$this, 'customizer']);
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

    public function settings(): void
    {
        register_setting('corbidev_group', 'corbidev_primary_color');
    }

    public function customizer($wp_customize): void
    {
        $wp_customize->add_section('corbidev_section', [
            'title' => esc_html__('CorbiDev Settings', 'corbidevtheme'),
        ]);

        $wp_customize->add_setting('corbidev_primary_color');

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'corbidev_primary_color',
                [
                    'label' => esc_html__('Primary Color', 'corbidevtheme'),
                    'section' => 'corbidev_section',
                ]
            )
        );
    }

    public function render(): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Theme Settings', 'corbidevtheme'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('corbidev_group');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
