<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

/**
 * Extension complète de la gestion Apparence.
 * Ajoute Media Picker, Color Picker et sections Settings API.
 * Rétro-compatible avec la version précédente.
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
        add_action('admin_enqueue_scripts', [$this, 'assets']);
        add_action('wp_head', [$this, 'injectCssVariables']);
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
        register_setting('corbidev_group', 'corbidev_logo');
        register_setting('corbidev_group', 'corbidev_primary_color');
        register_setting('corbidev_group', 'corbidev_secondary_color');

        add_settings_section(
            'corbidev_brand',
            esc_html__('Brand Settings', 'corbidevtheme'),
            '__return_false',
            'corbidev-theme-settings'
        );

        add_settings_field(
            'corbidev_logo',
            esc_html__('Logo', 'corbidevtheme'),
            [$this, 'logoField'],
            'corbidev-theme-settings',
            'corbidev_brand'
        );

        add_settings_field(
            'corbidev_primary_color',
            esc_html__('Primary Color', 'corbidevtheme'),
            [$this, 'primaryColorField'],
            'corbidev-theme-settings',
            'corbidev_brand'
        );

        add_settings_field(
            'corbidev_secondary_color',
            esc_html__('Secondary Color', 'corbidevtheme'),
            [$this, 'secondaryColorField'],
            'corbidev-theme-settings',
            'corbidev_brand'
        );
    }

    public function assets(): void
    {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    public function logoField(): void
    {
        $value = esc_attr(get_option('corbidev_logo'));
        echo '<input type="text" id="corbidev_logo" name="corbidev_logo" value="' . $value . '" class="regular-text" />';
        echo '<button class="button" id="corbidev_logo_button">' . esc_html__('Select', 'corbidevtheme') . '</button>';
        ?>
        <script>
        jQuery(document).ready(function($){
            $('#corbidev_logo_button').on('click', function(e){
                e.preventDefault();
                var frame = wp.media({ title: 'Select Logo', multiple: false });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#corbidev_logo').val(attachment.url);
                });
                frame.open();
            });
        });
        </script>
        <?php
    }

    public function primaryColorField(): void
    {
        $value = esc_attr(get_option('corbidev_primary_color'));
        echo '<input type="text" name="corbidev_primary_color" value="' . $value . '" class="color-field" />';
        echo "<script>jQuery(function($){$('.color-field').wpColorPicker();});</script>";
    }

    public function secondaryColorField(): void
    {
        $value = esc_attr(get_option('corbidev_secondary_color'));
        echo '<input type="text" name="corbidev_secondary_color" value="' . $value . '" class="color-field" />';
    }

    public function injectCssVariables(): void
    {
        $primary = esc_attr(get_option('corbidev_primary_color', '#000000'));
        $secondary = esc_attr(get_option('corbidev_secondary_color', '#ffffff'));
        echo "<style>:root{--corbidev-primary:{$primary};--corbidev-secondary:{$secondary};}</style>";
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
