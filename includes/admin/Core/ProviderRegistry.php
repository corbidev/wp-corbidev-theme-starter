<?php
namespace CorbiDev\Theme\Admin\Core;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;

/**
 * Auto-découverte et enregistrement automatique des Providers.
 */
class ProviderRegistry
{
    public static function boot(string $providersPath, $repository): void
    {
        foreach (glob($providersPath . '/*.php') as $file) {
            require_once $file;
            $class = "CorbiDev\\Theme\\Admin\\Providers\\" . basename($file, '.php');

            if (class_exists($class) && is_subclass_of($class, ProviderInterface::class)) {
                (new $class($repository))->register();
            }
        }
    }
}
