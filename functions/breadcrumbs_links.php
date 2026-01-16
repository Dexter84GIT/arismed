<?php 
defined('ABSPATH') || exit;

add_filter('woocommerce_get_breadcrumb', function ($crumbs) {
    if (!function_exists('wc_get_page_id')) return $crumbs;

    $shop_id = wc_get_page_id('shop');
    if ($shop_id <= 0) return $crumbs;

    if (
        !(function_exists('is_product_category') && is_product_category()) &&
        !(function_exists('is_product') && is_product())
    ) {
        return $crumbs;
    }

    $shop_url = get_permalink($shop_id);
    $shop_title = get_the_title($shop_id);

    foreach ($crumbs as $c) {
        $u = isset($c[1]) ? (string) $c[1] : '';
        $t = isset($c[0]) ? (string) $c[0] : '';
        if (($u && untrailingslashit($u) === untrailingslashit($shop_url)) || ($t === $shop_title)) {
            return $crumbs;
        }
    }

    $shop = [$shop_title, $shop_url];

    $out = [];
    foreach ($crumbs as $i => $c) {
        $out[] = $c;
        if ($i === 0) $out[] = $shop;
    }

    return $out;
}, 10);
