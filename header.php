<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    /**
     * CSS critique inline
     * (bridge thème → Kernel)
     */
    // corbidev_critical_css();
    ?>
    <script>
        (function() {
            var storageKey = 'theme';
            var allowedThemes = {
                light: true,
                dark: true
            };
            var serverTheme = <?php
                                $initialServerTheme = null;
                                if (is_user_logged_in()) {
                                    $savedTheme = get_user_meta(get_current_user_id(), 'corbidev_theme_mode', true);
                                    if (in_array($savedTheme, ['light', 'dark'], true)) {
                                        $initialServerTheme = $savedTheme;
                                    }
                                }
                                echo wp_json_encode($initialServerTheme);
                                ?>;
            var storedTheme = null;

            try {
                storedTheme = localStorage.getItem(storageKey);
            } catch (e) {
                storedTheme = null;
            }

            var themeToApply = allowedThemes[storedTheme] ? storedTheme : (allowedThemes[serverTheme] ? serverTheme :
                null);

            if (themeToApply === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (themeToApply === 'light') {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    /**
     * Hook WordPress officiel
     * DOIT être appelé immédiatement après <body>
     */
    wp_body_open();
    ?>