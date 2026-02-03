<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap">
    <h1><?php echo esc_html__('Theme configuration', 'corbidevtheme'); ?></h1>
    <form method="post">
        <?php wp_nonce_field('corbidev_theme_config'); ?>
        <table class="form-table">
            <tr>
                <th><?php echo esc_html__('Dark mode', 'corbidevtheme'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="dark_mode" <?php checked($config['dark_mode'] ?? false); ?>>
                        <?php echo esc_html__('Enable dark mode', 'corbidevtheme'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
