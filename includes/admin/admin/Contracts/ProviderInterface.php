<?php
namespace CorbiDev\Theme\Admin\Contracts;

/**
 * Interface des Providers Admin auto-découverts.
 */
interface ProviderInterface
{
    public function register(): void;
}
