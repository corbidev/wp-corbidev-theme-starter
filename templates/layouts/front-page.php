<?php if (!defined('ABSPATH')) exit; ?>
<main class="layout-main">
<h1 class="title-main">
<?php echo esc_html__('CorbiDev Starter Theme', 'corbidevtheme'); ?>
</h1>
<div id="app"
     data-initial='<?php echo esc_attr(wp_json_encode([
         "description" => get_bloginfo("description")
     ])); ?>'>
</div>
</main>
