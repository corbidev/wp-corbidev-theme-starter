<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Gestion du consentement cookies.
 */
class CookieManager
{
    public static function renderBanner(): void
    {
        if (!get_option('corbidev_enable_cookies')) {
            return;
        }
        ?>
        <div id="corbidev-cookie-banner" style="position:fixed;bottom:0;width:100%;background:#111;color:#fff;padding:20px;">
            <span><?php echo esc_html__('This website uses cookies.', 'corbidevtheme'); ?></span>
            <button id="corbidev-cookie-accept"><?php echo esc_html__('Accept', 'corbidevtheme'); ?></button>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('corbidev-cookie-accept').onclick = function() {
                localStorage.setItem('corbidev_cookie', 'accepted');
                document.getElementById('corbidev-cookie-banner').remove();
            };
        });
        </script>
        <?php
    }
}
