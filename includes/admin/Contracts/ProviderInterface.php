<?php
namespace CorbiDev\Theme\Admin\Contracts;

/**
 * Interface des Providers Admin auto-découverts.
 * 
 * Chaque module doit implémenter cette interface
 * afin d’être automatiquement chargé par le AdminKernel.
 */
interface ProviderInterface
{
    /**
     * Enregistre les hooks WordPress du module.
     *
     * @return void
     */
    public function register(): void;
}
