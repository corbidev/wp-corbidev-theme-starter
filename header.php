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
    corbidev_critical_css();
    ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
    /**
     * Hook WordPress officiel
     * DOIT être appelé en premier après <body>
     */
    wp_body_open();

    /**
     * Loader progressif (thème)
     */
    corbidev_progressive_loader();
?>
