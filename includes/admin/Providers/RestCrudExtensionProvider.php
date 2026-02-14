<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Support\CapabilityGuard;

/**
 * Extension REST CRUD non destructive.
 */
class RestCrudExtensionProvider implements ProviderInterface
{
    public function __construct(private $repository) {}

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void
    {
        register_rest_route('corbidev/v1', '/options/(?P<key>[a-zA-Z0-9_-]+)', [
            'methods'  => 'POST',
            'callback' => [$this, 'update'],
            'permission_callback' => fn() => CapabilityGuard::canManage(),
        ]);
    }

    public function update($request)
    {
        $key = sanitize_text_field($request['key']);
        $value = sanitize_text_field($request->get_param('value'));

        update_option('corbidev_' . $key, $value);

        return rest_ensure_response(['updated' => true]);
    }
}
