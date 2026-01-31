<?php
function arismed_render_cart_page_total() {
    if (!WC()->cart) return;
    $total = (float) WC()->cart->get_total('edit');
    $currency = get_woocommerce_currency_symbol();
    echo esc_html(number_format_i18n($total, 0)) . '<span class="currency">' . esc_html($currency) . '</span>';
}