<?php
namespace CorbiDev\Theme\Admin;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;

/**
 * Kernel principal de la couche Admin.
 *
 * Gère l’auto-découverte des Providers.
 */
class AdminKernel
{
    /**
     * Repository des options.
     */
    private SettingsRepository $repository;

    /**
     * Constructeur.
     */
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
        add_action('admin_init', [$this, 'loadProviders']);
    }

    /**
     * Charge automatiquement tous les Providers.
     *
     * @return void
     */
    public function loadProviders(): void
    {
        foreach (glob(__DIR__ . '/Providers/*.php') as $file) {

            require_once $file;

            $class = "CorbiDev\\Theme\\Admin\\Providers\\" . basename($file, '.php');

            if (class_exists($class) &&
                is_subclass_of($class, ProviderInterface::class)) {

                (new $class($this->repository))->register();
            }
        }
    }
}