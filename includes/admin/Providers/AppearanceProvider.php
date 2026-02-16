<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

/**
 * v1.0 Stable Core – Appearance management.
 * Minimal native WordPress implementation.
 */
class AppearanceProvider implements ProviderInterface
{
    public function __construct($repository) {}

    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
    }

    public function menu(): void
    {
        if (!CapabilityGuard::canManage()) {
            return;
        }

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
        register_setting('corbidev_group', 'corbidev_logo');

        add_settings_section(
            'corbidev_brand_section',
            esc_html__('Brand Settings', 'corbidevtheme'),
            '__return_false',
            'corbidev-theme-settings'
        );

        add_settings_field(
            'corbidev_primary_color',
            esc_html__('Primary Color', 'corbidevtheme'),
            [$this, 'primaryColorField'],
            'corbidev-theme-settings',
            'corbidev_brand_section'
        );

        add_settings_field(
            'corbidev_logo',
            esc_html__('Logo', 'corbidevtheme'),
            [$this, 'logoField'],
            'corbidev-theme-settings',
            'corbidev_brand_section'
        );
    }

    public function enqueueAssets($hook): void
    {
        if ($hook !== 'appearance_page_corbidev-theme-settings') {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_add_inline_script('wp-color-picker', "
            jQuery(document).ready(function($){
                $('.corbidev-color-field').wpColorPicker();

                $('.corbidev-upload-button').on('click', function(e){
                    e.preventDefault();

                    const button = $(this);
                    const target = $('#' + button.data('target'));

                    const frame = wp.media({
                        title: 'Select Image',
                        multiple: false
                    });

                    frame.on('select', function(){
                        const attachment = frame.state().get('selection').first().toJSON();
                        target.val(attachment.url);
                    });

                    frame.open();
                });
            });
        ");
    }

    public function primaryColorField(): void
    {
        $value = esc_attr(get_option('corbidev_primary_color'));
        echo '<input type="text" name="corbidev_primary_color" value="' . $value . '" class="corbidev-color-field" data-default-color="#000000" />';
    }

    public function logoField(): void
    {
        $value = esc_attr(get_option('corbidev_logo'));

        echo '<input type="text" id="corbidev_logo" name="corbidev_logo" value="' . $value . '" class="regular-text" />';
        echo '<button type="button" class="button corbidev-upload-button" data-target="corbidev_logo">';
        echo esc_html__('Select Logo', 'corbidevtheme');
        echo '</button>';
    }

    public function render(): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Theme Settings', 'corbidevtheme'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('corbidev_group');
                do_settings_sections('corbidev-theme-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
