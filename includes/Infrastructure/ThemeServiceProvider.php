<?php

declare(strict_types=1);

namespace CorbiDev\Theme\Infrastructure;

use CorbiDev\Kernel\Contracts\ServiceProviderInterface;
use CorbiDev\Kernel\Container\Container;
use CorbiDev\Kernel\Events\EventDispatcher;
use CorbiDev\Kernel\Events\Event;
use CorbiDev\Theme\Services\OptimizedAssetsService;
use CorbiDev\Theme\Services\WordPressCleanupService;
use CorbiDev\Theme\Services\ThemeContextService;
use CorbiDev\Theme\Services\NavigationService;
use CorbiDev\Theme\Services\CurrentUserService;
use CorbiDev\Theme\Services\ThemeConfigService;
use CorbiDev\Theme\Services\ThemeConfigWriterService;

/**
 * Service Provider principal du thème CorbiDev - Version Optimisée
 *
 * Enregistre tous les services du thème dans le conteneur
 * avec optimisations de performance intégrées.
 */
final class ThemeServiceProvider implements ServiceProviderInterface
{
    /**
     * Enregistrement des services dans le conteneur
     *
     * @param Container $container Conteneur d'injection de dépendances
     * @return void
     */
    public function register(Container $container): void
    {
        // Services métier
        $container->set(
            ThemeContextService::class,
            new ThemeContextService('starter')
        );

        $container->set(
            NavigationService::class,
            new NavigationService()
        );

        $container->set(
            CurrentUserService::class,
            new CurrentUserService()
        );

        $container->set(
            ThemeConfigService::class,
            new ThemeConfigService()
        );

        $container->set(
            ThemeConfigWriterService::class,
            new ThemeConfigWriterService()
        );

        // Services d'optimisation
        $container->set(
            OptimizedAssetsService::class,
            new OptimizedAssetsService()
        );

        $container->set(
            WordPressCleanupService::class,
            new WordPressCleanupService()
        );

        // Configuration des événements kernel
        $this->registerEventListeners($container);
    }

    /**
     * Boot du thème avec optimisations
     *
     * @param Container $container Conteneur d'injection de dépendances
     * @return void
     */
    public function boot(Container $container): void
    {
        // === NAVIGATION ===
        add_action(
            'after_setup_theme',
            [$container->get(NavigationService::class), 'registerMenus']
        );

        // === OPTIMISATIONS WORDPRESS ===
        $cleanup = $container->get(WordPressCleanupService::class);
        
        // Activer toutes les optimisations d'un coup
        add_action('init', [$cleanup, 'enableAllOptimizations']);
        
        // Désactiver XML-RPC (sécurité)
        add_action('init', [$cleanup, 'disableXmlRpc']);

        // === ASSETS OPTIMISÉS ===
        $assets = $container->get(OptimizedAssetsService::class);
        
        // Précharger les ressources critiques dans le <head>
        add_action('wp_head', [$assets, 'preloadCriticalAssets'], 1);
        
        // Charger les assets frontend
        add_action('wp_enqueue_scripts', function () use ($assets) {
            if (!is_admin()) {
                $assets->enqueueFrontendAssets();
            }
        }, 10);
        
        // Charger les assets admin
        add_action('admin_enqueue_scripts', function () use ($assets) {
            $assets->enqueueAdminAssets();
        }, 10);

        // === SUPPORT THÈME ===
        $this->registerThemeSupport();

        // === ÉVÉNEMENT PERSONNALISÉ ===
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->get(EventDispatcher::class);
        $dispatcher->dispatch('theme.booted', [
            'theme' => 'starter',
            'optimizations_enabled' => true,
        ]);
    }

    /**
     * Enregistre les listeners sur les événements du kernel
     *
     * @param Container $container Conteneur d'injection de dépendances
     * @return void
     */
    private function registerEventListeners(Container $container): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->get(EventDispatcher::class);

        // Logger le boot complet du kernel
        $dispatcher->on('kernel.booted', function (Event $event) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf(
                    '[CorbiDev Optimized] Kernel booted with %d providers',
                    $event->get('providers_count', 0)
                ));
            }
        });

        // Événement déclenché avant l'enregistrement de chaque provider
        $dispatcher->on('kernel.provider.registering', function (Event $event) {
            if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log(sprintf(
                    '[CorbiDev Optimized] Registering: %s',
                    $event->get('provider', 'unknown')
                ));
            }
        });
    }

    /**
     * Enregistre le support des fonctionnalités WordPress
     *
     * @return void
     */
    private function registerThemeSupport(): void
    {
        add_action('after_setup_theme', function () {
            // Support des images mises en avant
            add_theme_support('post-thumbnails');

            // Support du titre dynamique
            add_theme_support('title-tag');

            // Support HTML5
            add_theme_support('html5', [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]);

            // Support des liens de flux automatiques
            add_theme_support('automatic-feed-links');

            // Support responsive embeds
            add_theme_support('responsive-embeds');

            // Désactiver l'éditeur de blocs si non utilisé
            // add_theme_support('disable-custom-colors');
            // add_theme_support('disable-custom-font-sizes');
        });
    }
}
