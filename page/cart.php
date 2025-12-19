<?php
/*
Template Name: Корзина
*/
defined('ABSPATH') || exit;

if (!function_exists('WC')) exit;

$cart = WC()->cart;

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class('page page-cart arismed-plain'); ?>>
<?php wp_body_open(); ?>
<?php get_header(); ?>

<main class="page">
  <?php include get_stylesheet_directory() . '/inc/page-checkout/parts/cart-view.php'; ?>
</main>

<?php wp_footer(); ?>
<?php get_footer(); ?>
</body>
</html>
