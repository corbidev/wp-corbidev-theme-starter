
<?php
namespace CorbiDev\Theme\Admin\Support;

/**
 * Helper pour notifier les futures dépréciations sans casser l'API.
 */
class DeprecationHelper
{
    public static function notice(string $message): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            trigger_error($message, E_USER_DEPRECATED);
        }
    }
}
