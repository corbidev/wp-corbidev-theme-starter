<?php
/**
 * Theme bootstrap file
 *
 * Point d’entrée unique du thème CorbiDev.
 *
 * @package CorbiDevTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Kernel bootstrap
|--------------------------------------------------------------------------
*/

use CorbiDev\Theme\Kernel;
use CorbiDev\Kernel\Loading\CriticalCssService;

if (class_exists(Kernel::class)) {
    Kernel::boot([
        'theme' => 'starter',
    ]);
}

/*
|--------------------------------------------------------------------------
| Theme → Kernel bridge
|--------------------------------------------------------------------------
*/

/**
 * Injecte le CSS critique dans le <head>.
 *
 * @return void
 */
function corbidev_critical_css(): void
{
    if (!class_exists(CriticalCssService::class)) {
        return;
    }

    /**
     * IMPORTANT :
     * Le service CriticalCssService dépend du chemin du thème.
     * On injecte explicitement le contexte ici.
     */
    $service = new CriticalCssService(get_template_directory());

    $css = $service->getCriticalCss();

    if ($css === '') {
        return;
    }

    echo '<style id="corbidev-critical-css">';
    echo $css;
    echo '</style>';
}

/*
|--------------------------------------------------------------------------
| Sécurité minimale
|--------------------------------------------------------------------------
*/

add_action('after_setup_theme', static function (): void {
    if (!defined('ABSPATH')) {
        exit;
    }
});
