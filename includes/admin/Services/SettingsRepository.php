<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Repository centralisé des options du thème.
 * 
 * Compatible multisite.
 */
class SettingsRepository
{
    /**
     * Préfixe des options.
     */
    private string $prefix = 'corbidev_';

    /**
     * Récupère une option.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $option = is_multisite()
            ? get_site_option($this->prefix . $key)
            : get_option($this->prefix . $key);

        return $option !== false ? $option : $default;
    }

    /**
     * Définit une option.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        if (is_multisite()) {
            update_site_option($this->prefix . $key, $value);
        } else {
            update_option($this->prefix . $key, $value);
        }
    }
}
