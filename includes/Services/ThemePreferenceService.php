<?php

namespace CorbiDev\Services;

use WP_REST_Request;
use WP_REST_Response;

if (!defined('ABSPATH')) {
    exit;
}

class ThemePreferenceService
{
    private const USER_THEME_META_KEY = 'corbidev_theme_mode';

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(): void
    {
        register_rest_route('corbidev/v1', '/theme-preference', [
            'methods'             => 'POST',
            'callback'            => [$this, 'saveThemePreference'],
            'permission_callback' => fn() => is_user_logged_in(),
            'args'                => [
                'theme' => [
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'validate_callback' => fn($value) => in_array($value, ['light', 'dark'], true),
                ],
            ],
        ]);
    }

    public function saveThemePreference(WP_REST_Request $request): WP_REST_Response
    {
        $theme = $request->get_param('theme');

        if (!in_array($theme, ['light', 'dark'], true)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Invalid theme value.',
            ], 400);
        }

        update_user_meta(get_current_user_id(), self::USER_THEME_META_KEY, $theme);

        return new WP_REST_Response([
            'success' => true,
            'theme'   => $theme,
        ]);
    }
}
