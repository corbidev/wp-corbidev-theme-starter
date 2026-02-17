<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/includes/autoload.php';

use CorbiDev\Core\Theme;

(new Theme())->boot();