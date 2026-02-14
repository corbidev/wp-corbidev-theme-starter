<?php
namespace CorbiDev\Theme\Admin\Support;

/**
 * Classe de contrôle centralisé des permissions.
 * 
 * Permet de sécuriser tous les accès aux paramètres du thème.
 */
class CapabilityGuard
{
    /**
     * Vérifie si l'utilisateur courant peut gérer les options.
     *
     * @return bool
     */
    public static function canManage(): bool
    {
        return current_user_can('manage_options');
    }
}
