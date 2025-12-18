<?php
defined('ABSPATH') || exit;

$cart = WC()->cart;
$currency = get_woocommerce_currency_symbol();
?>

<section class="cart section">
    <div class="container df fdc gap30">
        <h2 class="sectionTitle druk"><?php esc_html_e('Корзина', 'woocommerce'); ?></h2>

        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
            <div class="content df fdc gap10">
                <?php if ($cart && !$cart->is_empty()) : ?>
                    <?php foreach ($cart->get_cart() as $cart_item_key => $cart_item) :
                        $product = $cart_item['data'] ?? null;
                        if (!$product || !$product->exists()) continue;

                        $product_id = (int) ($cart_item['product_id'] ?? 0);
                        $qty = (int) ($cart_item['quantity'] ?? 0);
                        if ($qty <= 0) continue;

                        $permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                        $name = $product->get_name();
                        $img = $product->get_image('woocommerce_thumbnail');

                        $price = (float) wc_get_price_to_display($product);
                        $reg = (float) wc_get_price_to_display($product, ['price' => $product->get_regular_price()]);
                        $has_sale = $reg > 0 && $price < $reg;

                        $remove_url = wc_get_cart_remove_url($cart_item_key);
                        $min = 1;
                        $max = $product->get_max_purchase_quantity();
                        if (!$max || $max < 1) $max = 9999;
                    ?>
                        <div class="row unit df aic gap60" data-key="<?php echo esc_attr($cart_item_key); ?>">
                            <div class="block df aic gap30">
                                <div class="img">
                                    <?php if ($permalink) : ?>
                                        <a href="<?php echo esc_url($permalink); ?>"><?php echo $img; ?></a>
                                    <?php else : ?>
                                        <?php echo $img; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($permalink) : ?>
                                    <a class="name druk" href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($name); ?></a>
                                <?php else : ?>
                                    <p class="name druk"><?php echo esc_html($name); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="block df aic gap30">
                                <?php if ($has_sale) : ?>
                                    <p class="old"><?php echo wp_kses_post(wc_price($reg)); ?></p>
                                <?php endif; ?>
                                <p class="new druk"><?php echo wp_kses_post(wc_price($price)); ?></p>
                            </div>

                            <div class="quantity df aic gap20" data-min="<?php echo esc_attr($min); ?>" data-max="<?php echo esc_attr($max); ?>">
                                <div class="dec btn" role="button" tabindex="0">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.2498 9.74854H3.74976V8.24854H14.2498V9.74854Z" />
                                    </svg>
                                </div>

                                <p class="count"><?php echo esc_html((string) $qty); ?></p>

                                <div class="inc btn" role="button" tabindex="0">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.2498 9.74976H9.74976V14.2498H8.24976V9.74976H3.74976V8.24976H8.24976V3.74976H9.74976V8.24976H14.2498V9.74976Z" />
                                    </svg>
                                </div>

                                <input
                                    type="number"
                                    class="qtyInput"
                                    name="<?php echo esc_attr("cart[{$cart_item_key}][qty]"); ?>"
                                    value="<?php echo esc_attr((string) $qty); ?>"
                                    min="<?php echo esc_attr((string) $min); ?>"
                                    max="<?php echo esc_attr((string) $max); ?>"
                                    step="1"
                                    inputmode="numeric"
                                    style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;"
                                >
                            </div>

                            <a class="cancel df aic jcc" href="<?php echo esc_url($remove_url); ?>" aria-label="<?php esc_attr_e('Удалить', 'woocommerce'); ?>">
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" />
                                </svg>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="empty"><?php esc_html_e('Корзина пуста', 'woocommerce'); ?></p>
                <?php endif; ?>
            </div>

            <div class="controls df fdc gap40">
                <p class="legend overall df aic gap20">
                    <?php esc_html_e('Итого:', 'woocommerce'); ?>
                    <span class="sum"><?php echo wp_kses_post($cart ? $cart->get_cart_total() : wc_price(0)); ?></span>
                </p>

                <button type="submit" name="update_cart" value="1" class="btn" style="display:none;"></button>

                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="toCheckout btn df aic gap10" id="toCheckout">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 0L4.9425 1.0575L9.1275 5.25H0V6.75H9.1275L4.9425 10.9425L6 12L12 6L6 0Z" />
                    </svg>
                    <span><?php esc_html_e('Перейти к оформлению заказа', 'woocommerce'); ?></span>
                </a>
            </div>

            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
        </form>
    </div>
</section>

