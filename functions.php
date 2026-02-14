<?php
/**
 * Theme bootstrap file
 *
 * Point d’entrée unique du thème CorbiDev.
 * - Boot du Kernel
 * - Bridges thème → Kernel
 *
 * @package CorbiDevTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

use CorbiDev\Kernel\Theme\Kernel;
use CorbiDev\Kernel\Loading\CriticalCssService;

/*
|--------------------------------------------------------------------------
| Kernel bootstrap
|--------------------------------------------------------------------------
|
| Le Kernel est une dépendance PHP partagée.
| Il ne produit aucun rendu HTML.
|
*/

if (class_exists(Kernel::class)) {
    Kernel::boot([
        'theme' => 'starter',
    ]);
}

/*
|--------------------------------------------------------------------------
| Theme → Kernel bridge
|--------------------------------------------------------------------------
|
| Les templates WordPress n’accèdent JAMAIS directement au Kernel.
| Toute interaction passe par une fonction globale du thème.
|
*/

/**
 * Injecte le CSS critique dans le <head>.
 *
 * - Le Kernel calcule le CSS (PHP pur)
 * - Le thème gère le HTML
 *
 * @return void
 */
function corbidev_critical_css(): void
{
    if (!class_exists(CriticalCssService::class)) {
        return;
    }

    /**
     * Le Kernel attend un CHEMIN DE FICHIER,
     * jamais un dossier.
     */
    $criticalCssFile = get_template_directory() . '/assets/css/critical.css';

    if (!is_file($criticalCssFile)) {
        return;
    }

    $service = new CriticalCssService($criticalCssFile);
    $css = $service->render();

    if (!is_string($css) || $css === '') {
        return;
    }

    echo '<style id="corbidev-critical-css">';
    echo $css;
    echo '</style>';
}


add_action('after_setup_theme', static function (): void {
    if (!defined('ABSPATH')) {
        exit;
    }

    if (!is_admin()) {
        return;
    }

    if (class_exists(\CorbiDev\Theme\Admin\AdminKernel::class)) {
        $kernel = new \CorbiDev\Theme\Admin\AdminKernel();
        $kernel->boot();
    }

    if (
        class_exists(\CorbiDev\Theme\Infrastructure\Admin\ThemeConfigAdmin::class)
        && class_exists(\CorbiDev\Theme\Services\ThemeConfigService::class)
        && class_exists(\CorbiDev\Theme\Services\ThemeConfigWriterService::class)
    ) {
        $themeConfigAdmin = new \CorbiDev\Theme\Infrastructure\Admin\ThemeConfigAdmin(
            new \CorbiDev\Theme\Services\ThemeConfigService(),
            new \CorbiDev\Theme\Services\ThemeConfigWriterService()
        );

        $themeConfigAdmin->register();
    }
});