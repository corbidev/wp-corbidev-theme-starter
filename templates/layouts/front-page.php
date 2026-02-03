<?php if (!defined('ABSPATH')) exit; ?>
<main class="container mx-auto p-6">
<h1 class="text-3xl font-bold"><?php echo esc_html__('CorbiDev Starter Theme', 'corbidevtheme'); ?></h1>
<section id="app" data-items='<?php echo esc_attr(wp_json_encode(['description'=>get_bloginfo('description')])); ?>'>
<p class="text-gray-700"><?php echo esc_html(get_bloginfo('description')); ?></p>
<noscript><p class="text-sm text-gray-500"><?php echo esc_html__('JavaScript disabled.', 'corbidevtheme'); ?></p></noscript>
</section>
</main>
