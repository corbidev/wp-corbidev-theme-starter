<?php

namespace CorbiDev\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Service de gestion des menus
 */
class NavigationService
{
    public function getPrimaryMenu(): array
    {
        return wp_get_nav_menu_items('primary') ?: [];
    }
}