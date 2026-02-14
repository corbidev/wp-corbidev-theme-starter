<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Définition du schéma JSON des options thème.
 */
class SettingsSchema
{
    public static function schema(): array
    {
        return [
            'corbidev_primary_color' => [
                'type' => 'hex',
                'default' => '#000000',
            ],
            'corbidev_secondary_color' => [
                'type' => 'hex',
                'default' => '#ffffff',
            ],
            'corbidev_enable_cookies' => [
                'type' => 'boolean',
                'default' => false,
            ],
        ];
    }
}
