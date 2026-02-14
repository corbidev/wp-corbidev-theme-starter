<?php
namespace CorbiDev\Theme\Admin\Support;

/**
 * Vérification centralisée des permissions.
 */
class CapabilityGuard
{
    public static function canManage(): bool
    {
        return current_user_can('manage_options');
    }
}
