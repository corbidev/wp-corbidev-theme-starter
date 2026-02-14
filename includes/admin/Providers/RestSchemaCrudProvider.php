<?php
namespace CorbiDev\Theme\Admin\Providers;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;
use CorbiDev\Theme\Admin\Services\SettingsSchema;
use WP_REST_Request;
use WP_Error;

/**
 * REST CRUD sécurisé basé sur schéma JSON.
 */
class RestSchemaCrudProvider implements ProviderInterface
{
    private SettingsRepository $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

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
                'permission_callback' => fn() => current_user_can('manage_options'),
            ],
            [
                'methods'  => 'POST',
                'callback' => [$this, 'updateAll'],
                'permission_callback' => fn() => current_user_can('manage_options'),
            ],
        ]);
    }

    public function getAll()
    {
        $schema = SettingsSchema::schema();
        $data = [];

        foreach ($schema as $key => $rules) {
            $data[$key] = $this->repository->get($key, $rules['default']);
        }

        return rest_ensure_response($data);
    }

    public function updateAll(WP_REST_Request $request)
    {
        $schema = SettingsSchema::schema();
        $params = $request->get_json_params();

        if (!is_array($params)) {
            return new WP_Error('invalid_payload', 'Invalid JSON payload', ['status' => 400]);
        }

        foreach ($params as $key => $value) {

            if (!isset($schema[$key])) {
                continue;
            }

            $rules = $schema[$key];

            switch ($rules['type']) {
                case 'hex':
                    $sanitized = sanitize_hex_color($value);
                    break;

                case 'boolean':
                    $sanitized = (bool) $value;
                    break;

                default:
                    $sanitized = sanitize_text_field($value);
            }

            $this->repository->set($key, $sanitized);
        }

        return rest_ensure_response(['status' => 'updated']);
    }
}
