
<?php
namespace CorbiDev\Theme\Admin\Services;

/**
 * Gestion avancée du consentement cookies (RGPD ready).
 * Ajout non destructif.
 */
class AdvancedCookieManager
{
    public function renderBanner(string $message): void
    {
        ?>
        <div id="corbidev-cookie-banner" style="position:fixed;bottom:0;width:100%;background:#000;color:#fff;padding:15px;display:none;">
            <span><?php echo esc_html($message); ?></span>
            <button id="corbidev-accept"><?php echo esc_html__('Accept', 'corbidevtheme'); ?></button>
            <button id="corbidev-decline"><?php echo esc_html__('Decline', 'corbidevtheme'); ?></button>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!localStorage.getItem('corbidev_cookie_consent')) {
                document.getElementById('corbidev-cookie-banner').style.display = 'block';
            }
            document.getElementById('corbidev-accept').onclick = function() {
                localStorage.setItem('corbidev_cookie_consent', 'accepted');
                document.getElementById('corbidev-cookie-banner').remove();
            };
            document.getElementById('corbidev-decline').onclick = function() {
                localStorage.setItem('corbidev_cookie_consent', 'declined');
                document.getElementById('corbidev-cookie-banner').remove();
            };
        });
        </script>
        <?php
    }
}
