<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * REST CRUD complet pour options thème.
 */
class RestCrudProvider implements ProviderInterface
{
    public function __construct(private $repository){}

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void
    {
        register_rest_route('corbidev/v1', '/options', [
            [
                'methods'  => 'GET',
                'callback' => [$this, 'getAll'],
                'permission_callback' => fn() => current_user_can('manage_options')
            ],
            [
                'methods'  => 'POST',
                'callback' => [$this, 'update'],
                'permission_callback' => fn() => current_user_can('manage_options')
            ]
        ]);
    }

    public function getAll()
    {
        return rest_ensure_response(get_option('corbidev_primary_color'));
    }

    public function update($request)
    {
        foreach ($request->get_params() as $key => $value) {
            update_option($key, sanitize_text_field($value));
        }

        return rest_ensure_response(['status' => 'updated']);
    }
}
