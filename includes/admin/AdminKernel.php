<?php
namespace CorbiDev\Theme\Admin;

use CorbiDev\Theme\Admin\Contracts\ProviderInterface;
use CorbiDev\Theme\Admin\Services\SettingsRepository;

/**
 * Kernel principal Admin CorbiDev.
 */
class AdminKernel
{
    private SettingsRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingsRepository();
    }

    public function boot(): void
    {
        $this->loadProviders();
    }

    private function loadProviders(): void
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
