<?php

declare(strict_types=1);

namespace CorbiDev\Theme\Infrastructure;

use CorbiDev\Kernel\Contracts\ServiceProviderInterface;
use CorbiDev\Kernel\Container\Container;
use CorbiDev\Kernel\Events\EventDispatcher;
use CorbiDev\Kernel\Events\Event;
use CorbiDev\Theme\Services\ThemeContextService;
use CorbiDev\Theme\Services\NavigationService;
use CorbiDev\Theme\Services\CurrentUserService;
use CorbiDev\Theme\Services\ThemeConfigService;
use CorbiDev\Theme\Services\ThemeConfigWriterService;

/**
 * Service Provider principal du thème CorbiDev
 *
 * Version allégée : Le kernel v1.2.0 gère le chargement progressif.
 * Ce provider ne gère que les services métier spécifiques au thème.
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
        // Services métier du thème
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

        // Écouter les événements du kernel
        $this->registerEventListeners($container);
    }

    /**
     * Boot du thème
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

        // === SUPPORT THÈME ===
        $this->registerThemeSupport();

        // === ÉVÉNEMENT PERSONNALISÉ ===
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->get(EventDispatcher::class);
        $dispatcher->dispatch('theme.booted', [
            'theme' => 'starter',
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

        // Logger le boot du kernel en mode debug
        $dispatcher->on('kernel.booted', function (Event $event) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf(
                    '[Theme Starter] Kernel booted with %d providers',
                    $event->get('providers_count', 0)
                ));
            }
        });

        // Logger le chargement progressif
        $dispatcher->on('kernel.loading.booted', function (Event $event) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf(
                    '[Theme Starter] Progressive loading: %s',
                    $event->get('strategy', 'unknown')
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
        });
    }
}
