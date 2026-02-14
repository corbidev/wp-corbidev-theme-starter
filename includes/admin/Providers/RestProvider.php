<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * REST sécurisé CorbiDev.
 */
class RestProvider implements ProviderInterface
{
    public function __construct($repository) {}

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void
    {
        register_rest_route('corbidev/v1', '/options', [
            'methods'  => 'GET',
            'callback' => [$this, 'getOptions'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ]);
    }

    public function getOptions()
    {
        return rest_ensure_response([
            'primary_color' => get_option('corbidev_primary_color')
        ]);
    }
}
