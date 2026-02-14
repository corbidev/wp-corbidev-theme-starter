<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

/**
 * REST sécurisé avec vérification permissions.
 */
class EnterpriseRestSecureProvider implements ProviderInterface
{
    public function __construct(private $repository){}

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void
    {
        register_rest_route('corbidev/v1','/options', [
            'methods'=>'GET',
            'callback'=>[$this,'get'],
            'permission_callback'=>fn()=>CapabilityGuard::canManage(),
        ]);
    }

    public function get()
    {
        return rest_ensure_response(['status'=>'secure']);
    }
}
