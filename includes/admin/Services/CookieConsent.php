<?php
namespace CorbiDev\Theme\Admin\Services;

class CookieConsent
{
    public static function render(): void
    {
        ?>
        <div id="corbidev-cookie-banner" style="display:none;position:fixed;bottom:0;width:100%;background:#111;color:#fff;padding:20px;z-index:9999;">
            <span><?php echo esc_html__('This website uses cookies.', 'corbidevtheme'); ?></span>
            <button id="corbidev-accept"><?php echo esc_html__('Accept', 'corbidevtheme'); ?></button>
            <button id="corbidev-decline"><?php echo esc_html__('Decline', 'corbidevtheme'); ?></button>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function(){
            if(!localStorage.getItem('corbidev_cookie')){
                document.getElementById('corbidev-cookie-banner').style.display='block';
            }
            document.getElementById('corbidev-accept').onclick=function(){
                localStorage.setItem('corbidev_cookie','accepted');
                document.getElementById('corbidev-cookie-banner').remove();
            };
            document.getElementById('corbidev-decline').onclick=function(){
                localStorage.setItem('corbidev_cookie','declined');
                document.getElementById('corbidev-cookie-banner').remove();
            };
        });
        </script>
        <?php
    }
}
