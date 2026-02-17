<?php

namespace CorbiDev\Services;

if (!defined('ABSPATH')) {
    exit;
}

class ThemeContextService
{
    private NavigationService $navigation;

    public function __construct(NavigationService $navigation)
    {
        $this->navigation = $navigation;
    }

    public function getContext(): array
    {
        return [
            'siteName' => get_bloginfo('name'),
            'menu'     => $this->navigation->getPrimaryMenu()
        ];
    }
}