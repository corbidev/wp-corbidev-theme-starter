<?php
namespace CorbiDev\Theme\Admin\Core;

/**
 * Registre dynamique des onglets admin.
 */
class TabRegistry
{
    private static array $tabs = [];

    public static function register(array $tab): void
    {
        self::$tabs[] = $tab;
    }

    public static function all(): array
    {
        return self::$tabs;
    }
}
