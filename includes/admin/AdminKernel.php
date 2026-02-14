<?php
namespace CorbiDev\Theme\Admin;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;

/**
 * Kernel principal Admin CorbiDev.
 *
 * Charge les Providers AVANT les hooks admin_menu.
 */
class AdminKernel
{
    private SettingsRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingsRepository();
    }

    /**
     * Démarre le système Admin.
     *
     * @return void
     */
    public function boot(): void
    {
        // ⚠️ Chargement immédiat des Providers
        $this->loadProviders();
    }

    /**
     * Charge automatiquement tous les Providers.
     *
     * @return void
     */
    private function loadProviders(): void
    {
        foreach (glob(__DIR__ . '/Providers/*.php') as $file) {

            require_once $file;

            $class = "CorbiDev\\Theme\\Admin\\Providers\\" . basename($file, '.php');

            if (
                class_exists($class) &&
                is_subclass_of($class, ProviderInterface::class)
            ) {
                (new $class($this->repository))->register();
            }
        }
    }
}