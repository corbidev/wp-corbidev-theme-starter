<?php
/**
 * Theme bootstrap file
 *
 * Point d’entrée unique du thème CorbiDev.
 * - Boot du Kernel
 * - Enregistrement des bridges thème → Kernel
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
|
| Le Kernel est une dépendance PHP partagée.
| Il ne produit aucun rendu et ne dépend pas de WordPress.
|
*/

use CorbiDev\Theme\Kernel;
use CorbiDev\Kernel\Loading\CriticalCssService;

/**
 * Boot du Kernel CorbiDev
 */
if (class_exists(Kernel::class)) {
    Kernel::boot([
        'theme' => 'starter',
    ]);
}

/*
|--------------------------------------------------------------------------
| Theme → Kernel bridges
|--------------------------------------------------------------------------
|
| Les templates WordPress n’accèdent JAMAIS directement au Kernel.
| Toute exposition passe par une fonction globale du thème.
|
*/

/**
 * Injecte le Critical CSS dans le <head>
 *
 * - Appelé depuis header.php
 * - HTML autorisé ici uniquement
 *
 * @return void
 */
function corbidev_critical_css(): void
{
    if (!class_exists(CriticalCssService::class)) {
        return;
    }

    $service = new CriticalCssService();
    $css = $service->get();

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
|
| Empêche l’exécution directe de fichiers PHP du thème.
|
*/

add_action('after_setup_theme', static function (): void {
    if (!defined('ABSPATH')) {
        exit;
    }
});
