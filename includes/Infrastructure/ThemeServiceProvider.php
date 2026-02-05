<?php

declare(strict_types=1);

namespace CorbiDev\Theme\Infrastructure;

use CorbiDev\Kernel\Contracts\ServiceProviderInterface;
use CorbiDev\Kernel\Container\Container;
use CorbiDev\Kernel\Events\EventDispatcher;
use CorbiDev\Kernel\Events\Event;
use CorbiDev\Theme\Services\ThemeContextService;
use CorbiDev\Theme\Services\NavigationService;
use CorbiDev\Theme\Services\AssetsManifestService;
use CorbiDev\Theme\Services\CurrentUserService;
use CorbiDev\Theme\Services\ThemeConfigService;
use CorbiDev\Theme\Services\ThemeConfigWriterService;

/**
 * Service Provider principal du thème CorbiDev
 *
 * Enregistre tous les services du thème dans le conteneur
 * et configure le cycle de vie de l'application.
 */
final class ThemeServiceProvider implements ServiceProviderInterface
{
    /**
     * Enregistrement des services dans le conteneur
     *
     * Phase d'enregistrement : création et injection des services
     * dans le conteneur sans exécuter de logique métier.
     *
     * @param Container $container Conteneur d'injection de dépendances
     * @return void
     */
    public function register(Container $container): void
    {
        // Enregistrement du contexte thème
        $container->set(
            ThemeContextService::class,
            new ThemeContextService('starter')
        );

        // Enregistrement du service de navigation
        $container->set(
            NavigationService::class,
            new NavigationService()
        );

        // Enregistrement du service de gestion des assets
        $container->set(
            AssetsManifestService::class,
            new AssetsManifestService()
        );

        // Enregistrement du service utilisateur courant
        $container->set(
            CurrentUserService::class,
            new CurrentUserService()
        );

        // Enregistrement du service de configuration
        $container->set(
            ThemeConfigService::class,
            new ThemeConfigService()
        );

        // Enregistrement du service d'écriture de configuration
        $container->set(
            ThemeConfigWriterService::class,
            new ThemeConfigWriterService()
        );

        // Configuration des événements kernel
        $this->registerEventListeners($container);
    }

    /**
     * Boot du thème
     *
     * Phase de boot : enregistrement des hooks WordPress
     * et initialisation de la logique métier.
     *
     * @param Container $container Conteneur d'injection de dépendances
     * @return void
     */
    public function boot(Container $container): void
    {
        // Enregistrement des menus WordPress
        add_action(
            'after_setup_theme',
            [$container->get(NavigationService::class), 'registerMenus']
        );

        // Dispatch d'un événement personnalisé après boot du thème
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->get(EventDispatcher::class);
        $dispatcher->dispatch('theme.booted', [
            'theme' => 'starter',
            'services_registered' => 6,
        ]);
    }

    /**
     * Enregistre les listeners sur les événements du kernel
     *
     * Configure l'écoute des événements système pour logging,
     * monitoring et hooks personnalisés.
     *
     * @param Container $container Conteneur d'injection de dépendances
     * @return void
     */
    private function registerEventListeners(Container $container): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->get(EventDispatcher::class);

        // Logger le boot complet du kernel en mode debug
        $dispatcher->on('kernel.booted', function (Event $event) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf(
                    '[CorbiDev Theme Starter] Kernel booted successfully with %d providers',
                    $event->get('providers_count', 0)
                ));
            }
        });

        // Événement déclenché avant l'enregistrement de chaque provider
        $dispatcher->on('kernel.provider.registering', function (Event $event) {
            if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log(sprintf(
                    '[CorbiDev Theme Starter] Registering provider: %s',
                    $event->get('provider', 'unknown')
                ));
            }
        });
    }
}
