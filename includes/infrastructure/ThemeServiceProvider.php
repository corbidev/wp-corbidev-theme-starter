<?php

namespace CorbiDev\Theme\Infrastructure;

use CorbiDev\Kernel\Contracts\ServiceProviderInterface;
use CorbiDev\Kernel\Container\Container;
use CorbiDev\Theme\Services\ThemeContextService;
use CorbiDev\Theme\Services\NavigationService;
use CorbiDev\Theme\Services\AssetsManifestService;
use CorbiDev\Theme\Services\CurrentUserService;
use CorbiDev\Theme\Services\ThemeConfigService;
use CorbiDev\Theme\Services\ThemeConfigWriterService;

/**
 * Provider principal du thème CorbiDev
 */
final class ThemeServiceProvider implements ServiceProviderInterface
{
    /**
     * Enregistrement des services
     */
    public function register(Container $container): void
    {
        $container->set(
            ThemeContextService::class,
            new ThemeContextService('starter')
        );

        $container->set(
            NavigationService::class,
            new NavigationService()
        );

        $container->set(
            AssetsManifestService::class,
            new AssetsManifestService()
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
    }

    /**
     * Boot du thème
     */
    public function boot(Container $container): void
    {
        add_action(
            'after_setup_theme',
            [$container->get(NavigationService::class), 'registerMenus']
        );
    }
}
