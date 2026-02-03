<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoload PSR-4 minimal du thème CorbiDev
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'CorbiDev\\Theme\\';
    $baseDir = __DIR__ . '/includes/';

    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_readable($file)) {
            require_once $file;
        }
    }
});

use CorbiDev\Kernel\Theme\Kernel;

/**
 * Boot du kernel CorbiDev – Theme Starter
 */
Kernel::boot([
    'theme' => 'starter',
    'providers' => [
        CorbiDev\Theme\Infrastructure\ThemeServiceProvider::class,
    ],
]);
