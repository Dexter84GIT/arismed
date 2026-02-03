<?php
function arismed_render_cart_page_items() {
    $cart = WC()->cart;
    if (!$cart) return;

    $items = $cart->get_cart();
    $currency = get_woocommerce_currency_symbol();
    $decimals = (int) wc_get_price_decimals();

    $fmt_price = function ($v) use ($decimals) {
        $v = (float) $v;
        $use_decimals = $decimals > 0 && abs($v - round($v)) > 0.000001;
        return number_format_i18n($v, $use_decimals ? $decimals : 0);
    };

    if (!$items) {
        ?>
        <div class="row unit df aic gap60">
            <div class="block df aic gap30">
                <p class="name druk">Корзина пуста</p>
            </div>
        </div>
        <?php
        return;
    }

    foreach ($items as $cart_item_key => $cart_item) {
        $product = $cart_item['data'] ?? null;
        if (!$product || !$product->exists()) continue;

        $qty = (int) ($cart_item['quantity'] ?? 0);
        if ($qty <= 0) continue;

        $name = $product->get_name();

        $img_id = (int) $product->get_image_id();
        $img = $img_id ? wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail') : '';

        $product_id = (int) ($cart_item['product_id'] ?? 0);
        $link = $product_id ? get_permalink($product_id) : '';

        $price_now = (float) wc_get_price_to_display($product, ['qty' => 1]);
        $price_old = 0.0;

        if ($product->is_on_sale()) {
            $regular = (float) wc_get_price_to_display($product, ['qty' => 1, 'price' => $product->get_regular_price()]);
            if ($regular > 0 && $regular > $price_now) $price_old = $regular;
        }
        ?>
        <div class="row unit df aic gap60" data-key="<?php echo esc_attr($cart_item_key); ?>">
            <div class="block df aic gap30">
                <div class="img">
                    <?php if ($img) : ?>
                        <?php if ($link) : ?>
                            <a href="<?php echo esc_url($link); ?>">
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                            </a>
                        <?php else : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if ($link) : ?>
                    <a class="name druk" href="<?php echo esc_url($link); ?>"><?php echo esc_html($name); ?></a>
                <?php else : ?>
                    <p class="name druk"><?php echo esc_html($name); ?></p>
                <?php endif; ?>
            </div>

            <div class="block df aic gap30">
                <?php if ($price_old > 0) : ?>
                    <p class="old"><?php echo esc_html($fmt_price($price_old)); ?><span class="currency"><?php echo esc_html($currency); ?></span></p>
                <?php endif; ?>
                <p class="new druk"><?php echo esc_html($fmt_price($price_now)); ?><span class="currency"><?php echo esc_html($currency); ?></span></p>
            </div>

            <div class="quantity df aic gap20">
                <div class="dec btn" data-action="dec" role="button" tabindex="0" aria-label="Уменьшить">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2498 9.74854H3.74976V8.24854H14.2498V9.74854Z" />
                    </svg>
                </div>
                <p class="count" data-count><?php echo (int) $qty; ?></p>
                <div class="inc btn" data-action="inc" role="button" tabindex="0" aria-label="Увеличить">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2498 9.74976H9.74976V14.2498H8.24976V9.74976H3.74976V8.24976H8.24976V3.74976H9.74976V8.24976H14.2498V9.74976Z" />
                    </svg>
                </div>
            </div>

            <p class="cancel df aic jcc" data-action="remove" role="button" tabindex="0" aria-label="Удалить">
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" />
                </svg>
            </p>
        </div>
        <?php
    }
}