<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Repository des options (multisite ready).
 */
class SettingsRepository
{
    private string $prefix = 'corbidev_';

    public function get(string $key, $default = null)
    {
        $option = is_multisite()
            ? get_site_option($this->prefix . $key)
            : get_option($this->prefix . $key);

        return $option !== false ? $option : $default;
    }

    public function set(string $key, $value): void
    {
        if (is_multisite()) {
            update_site_option($this->prefix . $key, $value);
        } else {
            update_option($this->prefix . $key, $value);
        }
    }
}
