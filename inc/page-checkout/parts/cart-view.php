<?php
defined('ABSPATH') || exit;

if (!function_exists('WC')) return;

if (function_exists('wc_load_cart') && (null === WC()->cart || !WC()->cart)) {
    wc_load_cart();
}
if (!WC()->cart) return;

$nonce = wp_create_nonce('arismed_cart');
$checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '#';
?>
<section class="cart section" data-arismed-cart="1" data-nonce="<?php echo esc_attr($nonce); ?>">
    <div class="container df fdc gap30">
        <h2 class="sectionTitle druk">Корзина</h2>

        <div class="content df fdc gap10" data-cart-items>
            <?php arismed_render_cart_page_items(); ?>
        </div>

        <div class="controls df fdc gap40">
            <p class="legend overall df aic gap20">
                Итого:
                <span class="sum" data-cart-total><?php arismed_render_cart_page_total(); ?></span>
            </p>

            <a href="<?php echo esc_url($checkout_url); ?>" class="toCheckout btn df aic gap10" id="toCheckout">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 0L4.9425 1.0575L9.1275 5.25H0V6.75H9.1275L4.9425 10.9425L6 12L12 6L6 0Z" />
                </svg>
                <span class="druk">Перейти к оформлению</span>
            </a>
        </div>
    </div>
</section>
