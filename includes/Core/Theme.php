<?php

namespace CorbiDev\Core;

use CorbiDev\Services\NavigationService;
use CorbiDev\Services\ThemeContextService;
use CorbiDev\Services\AssetLoaderService;
use CorbiDev\Services\ThemePreferenceService;

class Theme
{
    private ThemeContextService $context;

    public function boot(): void
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('init', [$this, 'registerServices']);
    }

    public function setup(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        register_nav_menus([
            'primary' => __('Primary Menu', 'corbidevtheme')
        ]);
    }

    public function registerServices(): void
    {
        $navigation = new NavigationService();
        $this->context = new ThemeContextService($navigation);

        $assets = new AssetLoaderService();
        $assets->register();

        $themePreference = new ThemePreferenceService();
        $themePreference->register();
    }

    public function getContext(): ThemeContextService
    {
        return $this->context;
    }
}