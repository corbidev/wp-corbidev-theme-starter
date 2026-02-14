<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Injection automatique des variables CSS.
 */
class CssVariableInjector
{
    public static function inject(): void
    {
        $primary = esc_attr(get_option('corbidev_primary_color', '#000000'));
        $secondary = esc_attr(get_option('corbidev_secondary_color', '#ffffff'));

        echo "<style>:root{
            --corbidev-primary: {$primary};
            --corbidev-secondary: {$secondary};
        }</style>";
    }
}
