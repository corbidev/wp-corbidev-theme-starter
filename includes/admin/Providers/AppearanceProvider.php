
<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

class AppearanceProvider implements ProviderInterface
{
    public function __construct(private SettingsRepository $repository) {}

    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
    }

    public function menu(): void
    {
        if (!CapabilityGuard::canManage()) return;

        add_theme_page(
            esc_html__('Theme Settings', 'corbidevtheme'),
            esc_html__('Theme Settings', 'corbidevtheme'),
            'manage_options',
            'corbidev-settings',
            [$this, 'render']
        );
    }

    public function render(): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Theme Settings', 'corbidevtheme'); ?></h1>
            <p><?php echo esc_html__('Enterprise configuration module ready.', 'corbidevtheme'); ?></p>
        </div>
        <?php
    }
}
