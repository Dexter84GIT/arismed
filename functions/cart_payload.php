<?php
function arismed_cart_payload() {
    ob_start();
    arismed_render_mini_cart_items();
    $items = ob_get_clean();

    ob_start();
    arismed_render_mini_cart_total();
    $total = ob_get_clean();

    ob_start();
    arismed_render_mini_cart_count();
    $count_html = ob_get_clean();

    ob_start();
    arismed_render_cart_page_items();
    $cart_page_items_html = ob_get_clean();

    ob_start();
    arismed_render_cart_page_total();
    $cart_page_total_html = ob_get_clean();

    return [
        'ok' => true,
        'count' => (int) WC()->cart->get_cart_contents_count(),
        'count_html' => $count_html,
        'items_html' => $items,
        'total_html' => $total,
        'cart_page_items_html' => $cart_page_items_html,
        'cart_page_total_html' => $cart_page_total_html,
        'cart_hash' => WC()->cart->get_cart_hash(),
    ];
}