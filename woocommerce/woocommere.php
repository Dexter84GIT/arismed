<?php
defined('ABSPATH') || exit;
get_header();

if (is_shop()) {
    include get_stylesheet_directory() . '/inc/page-catalog/catalog.php';
} else {
    woocommerce_content();
}

get_footer();