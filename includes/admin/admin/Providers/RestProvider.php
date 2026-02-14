<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

/**
 * REST sécurisé (CRUD basique).
 */
class RestProvider implements ProviderInterface
{
    public function register(): void
    {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void
    {
        register_rest_route('corbidev/v1', '/options', [
            'methods' => 'GET',
            'callback' => [$this, 'getOptions'],
            'permission_callback' => fn() => CapabilityGuard::canManage(),
        ]);
    }

    public function getOptions()
    {
        return rest_ensure_response(['status' => 'ok']);
    }
}
