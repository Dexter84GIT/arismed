<?php
defined('ABSPATH') || exit;

get_header('shop');

if (is_shop()) {
    include get_stylesheet_directory() . '/inc/page-catalog/catalog.php';
} else {
    wc_get_template_part('archive', 'product');
}

get_footer('shop');