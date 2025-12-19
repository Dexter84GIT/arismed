<h2 class="sectionTitle druk"><?php single_term_title(); ?></h2>

<div class="content df fww ais gap20 category">
<?php
$term = get_queried_object();
$term_id = ($term && !is_wp_error($term) && !empty($term->term_id)) ? (int) $term->term_id : 0;

if (!$term_id) {
    echo '<p class="empty">Категория не определена</p>';
} else {
    $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

    $q = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 24,
        'paged' => $paged,
        'tax_query' => [[
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => [$term_id],
            'include_children' => true,
        ]],
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();

            $product = wc_get_product(get_the_ID());
            if (!$product) continue;

            $link_url = get_permalink($product->get_id());
            $title = $product->get_name();

            $img = $product->get_image('woocommerce_thumbnail', ['alt' => $title]);
            if (!$img) {
                $img = '<img src="' . esc_url(get_template_directory_uri() . '/img/catalog_001.png') . '" alt="' . esc_attr($title) . '">';
            }

            $price_html = $product->get_price_html();
            ?>
            <div class="item">
                <div class="top df fdc gap20">
                    <a class="img" href="<?php echo esc_url($link_url); ?>">
                        <?php echo $img; ?>
                    </a>
                    <a class="title druk" href="<?php echo esc_url($link_url); ?>">
                        <?php echo esc_html($title); ?>
                    </a>
                </div>
                <div class="bottom df aic jcsb">
                    <p class="price">
                        <?php echo wp_kses_post($price_html); ?>
                    </p>
                    <a
                        href="#"
                        class="btn ajaxAddToCart"
                        data-product_id="<?php echo esc_attr((string) $product->get_id()); ?>"
                        data-qty="1"
                    >В корзину</a>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p class="empty">Товаров пока нет</p>';
    }
}
?>
</div>
