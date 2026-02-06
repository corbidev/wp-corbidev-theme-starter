<?php if (!defined('ABSPATH')) exit; ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php corbidev_critical_css(); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php corbidev_progressive_loader(); ?>
<?php wp_body_open(); ?>
