<?php

declare(strict_types=1);

namespace CorbiDev\Theme\Services;

/**
 * Service de gestion de la navigation WordPress
 *
 * Gère l'enregistrement des emplacements de menus WordPress
 * et fournit des utilitaires pour la génération de navigation.
 */
final class NavigationService
{
    /**
     * Enregistre les emplacements de menus WordPress
     *
     * Cette méthode doit être appelée sur le hook 'after_setup_theme'
     * pour garantir que WordPress est prêt à enregistrer les menus.
     *
     * @return void
     */
    public function registerMenus(): void
    {
        register_nav_menus([
            'primary' => __('Primary Navigation', 'corbidevtheme'),
            'footer' => __('Footer Navigation', 'corbidevtheme'),
        ]);
    }

    /**
     * Récupère les items du menu principal
     *
     * @return array<int, object> Liste des items de menu
     */
    public function getPrimaryMenuItems(): array
    {
        $locations = get_nav_menu_locations();

        if (!isset($locations['primary'])) {
            return [];
        }

        $menu = wp_get_nav_menu_object($locations['primary']);

        if (!$menu) {
            return [];
        }

        return wp_get_nav_menu_items($menu->term_id) ?: [];
    }

    /**
     * Vérifie si un menu est assigné à un emplacement
     *
     * @param string $location Identifiant de l'emplacement (primary, footer, etc.)
     * @return bool
     */
    public function hasMenu(string $location): bool
    {
        $locations = get_nav_menu_locations();
        return isset($locations[$location]) && $locations[$location] !== 0;
    }
}
