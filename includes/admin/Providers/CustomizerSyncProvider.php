<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * Synchronisation Customizer ↔ Admin.
 */
class CustomizerSyncProvider implements ProviderInterface
{
    public function __construct($repository){}

    public function register(): void
    {
        add_action('customize_register', [$this, 'registerSettings']);
        add_action('customize_preview_init', [$this, 'previewScript']);
    }

    public function registerSettings($wp_customize): void
    {
        $wp_customize->add_setting('corbidev_primary_color', [
            'default'   => get_option('corbidev_primary_color'),
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'corbidev_primary_color_control',
                [
                    'label'    => esc_html__('Primary Color', 'corbidevtheme'),
                    'section'  => 'colors',
                    'settings' => 'corbidev_primary_color',
                ]
            )
        );
    }

    public function previewScript(): void
    {
        wp_enqueue_script(
            'corbidev-customizer-sync',
            get_template_directory_uri() . '/includes/admin/Assets/js/customizer-sync.js',
            ['customize-preview'],
            '1.0.0',
            true
        );
    }
}
