<?php
defined('ABSPATH') || exit;

$cart = (function_exists('WC') && WC()->cart) ? WC()->cart : null;

$count = $cart ? (int) $cart->get_cart_contents_count() : 0;
$currency = get_woocommerce_currency_symbol();

$decimals = (int) wc_get_price_decimals();

$total = 0;
if ($cart) {
    $total = (float) $cart->get_total('edit');
}
?>
<div class="miniCartBtn dropBtn">
    <div class="iconLink tpr">
        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.5 12C3.675 12 3.0075 12.675 3.0075 13.5C3.0075 14.325 3.675 15 4.5 15C5.325 15 6 14.325 6 13.5C6 12.675 5.325 12 4.5 12ZM12 12C11.175 12 10.5075 12.675 10.5075 13.5C10.5075 14.325 11.175 15 12 15C12.825 15 13.5 14.325 13.5 13.5C13.5 12.675 12.825 12 12 12ZM10.9125 8.25C11.475 8.25 11.97 7.9425 12.225 7.4775L14.91 2.61C15.1875 2.115 14.8275 1.5 14.2575 1.5H3.1575L2.4525 0H0V1.5H1.5L4.2 7.1925L3.1875 9.0225C2.64 10.0275 3.36 11.25 4.5 11.25H13.5V9.75H4.5L5.325 8.25H10.9125ZM3.87 3H12.9825L10.9125 6.75H5.6475L3.87 3Z"/>
        </svg>
        <p class="count"><?php echo esc_html($count); ?></p>
    </div>

    <div class="miniCart drop df fdc gap30">
        <div class="content df fdc gap10">
            <?php if ($cart && $count > 0) : ?>
                <?php foreach ($cart->get_cart() as $cart_item_key => $cart_item) :
                    $product = $cart_item['data'] ?? null;
                    if (!$product || !$product->exists()) continue;
                
                    $qty = (int) ($cart_item['quantity'] ?? 0);
                    if ($qty <= 0) continue;
                
                    $name = $product->get_name();
                
                    $img_id = (int) $product->get_image_id();
                    $img = $img_id ? wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail') : '';
                
                    $unit = (float) wc_get_price_to_display($product, ['qty' => 1]);
                    $decimals = (int) wc_get_price_decimals();
                    $unit_use_decimals = $decimals > 0 && abs($unit - round($unit)) > 0.000001;
                    $unit_str = number_format_i18n($unit, $unit_use_decimals ? $decimals : 0);
                ?>
                    <div class="item df aic gap20">
                        <div class="img">
                            <?php if ($img) : ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                            <?php endif; ?>
                        </div>
                            
                        <div class="text df fdc gap10">
                            <p class="name"><?php echo esc_html($name); ?></p>
                            <p class="cost druk">
                                <span class="number"><?php echo esc_html((string) $qty); ?></span>
                                <span>x</span>
                                <span class="sum"><?php echo esc_html($unit_str); ?><span class="currency"><?php echo esc_html($currency); ?></span></span>
                            </p>
                        </div>
                            
                        <span
                            class="delete"
                            data-key="<?php echo esc_attr($cart_item_key); ?>"
                            data-nonce="<?php echo esc_attr(wp_create_nonce('arismed_cart')); ?>"
                        ></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="empty">Корзина пуста</p>
            <?php endif; ?>
        </div>

        <div class="overall df aic gap20">
            <p class="legend">Итого:</p>
            <p class="druk sum">
                <?php echo esc_html(number_format_i18n($total, 0)); ?>
                <span class="currency"><?php echo esc_html($currency); ?></span>
            </p>
        </div>

        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="toCheckout df aic gap10 jcc">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 0L4.9425 1.0575L9.1275 5.25H0V6.75H9.1275L4.9425 10.9425L6 12L12 6L6 0Z" fill="white"/>
            </svg>
            <span class="druk">Оформить заказ</span>
        </a>
    </div>
</div>
