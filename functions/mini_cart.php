<?php
function arismed_mini_cart() {
    if (!arismed_cart_ensure_loaded()) {
        wp_send_json_error(['message' => 'Cart unavailable'], 400);
    }

    ob_start();
    include get_stylesheet_directory() . '/inc/shared/miniCart.php';
    $html = ob_get_clean();

    wp_send_json([
        'ok' => true,
        'html' => $html,
        'count' => (int) WC()->cart->get_cart_contents_count(),
    ]);
}