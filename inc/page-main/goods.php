        <section class="goods section mainGoods">
            <div class="wrap pr">
                <div class="container df fdc gap30">
                    <div class="top df aic jcsb">
                        <h2 class="sectionTitle druk">Товары</h2>
                    </div>
                    <div class="content swiper slider slider4 goodsSlider">
                        <div class="swiper-wrapper">
                        <?php
                        $q = new WP_Query([
                            'post_type' => 'product',
                            'post_status' => 'publish',
                            'posts_per_page' => 8,
                            'orderby' => 'rand',
                            'no_found_rows' => true,
                            'ignore_sticky_posts' => true,
                                'meta_query' => [
                                    [
                                        'key'     => 'feature',
                                        'value'   => '1',
                                        'compare' => '=',
                                    ],
                                ],
                            'tax_query' => [
                                [
                                    'taxonomy' => 'product_visibility',
                                    'field' => 'name',
                                    'terms' => ['exclude-from-catalog'],
                                    'operator' => 'NOT IN',
                                ],
                            ],
                        ]);
                        
                        if ($q->have_posts()) :
                            while ($q->have_posts()) : $q->the_post();
                                $product = wc_get_product(get_the_ID());
                                if (!$product || !$product->is_purchasable()) continue;
                        
                                $img_id = $product->get_image_id();
                                $img = $img_id ? wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, ['alt' => get_the_title()]) : '';
                        
                                $title = $product->get_name();
                                $price_html = $product->get_price_html();
                        
                                $add_url = esc_url($product->add_to_cart_url());
                                $link_url = esc_url(get_permalink($product->get_id()));
                        ?>
                            <div class="swiper-slide slide df fdc jcsb gap40" itemscope itemtype="https://schema.org/Product">
                                <div class="top df fdc gap20">
                                    <a class="img" href="<?php echo $link_url; ?>" itemprop="image">
                                        <?php echo $img; ?>
                                    </a>
                                    <a class="title druk" href="<?php echo $link_url; ?>" itemprop="name">
                                        <?php echo esc_html($title); ?>
                                    </a>
                                    <span class="brand" itemprop="brand"></span>
                                </div>
                                <div class="bottom df aic jcsb" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <p class="price" itemprop="price">
                                        <?php echo wp_kses_post($price_html); ?>
                                    </p>
                                    <meta itemprop="priceCurrency" content="RUB">
                                    <button
                                      type="button"
                                      class="btn addToCart"
                                      data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                                      data-qty="1"
                                    >В корзину</button>
                                    <link itemprop="availability" href="http://schema.org/InStock">
                                </div>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                        </div>
                        <div class="swiper-button-prev controlBtn prev">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0L9.41 1.41L3.83 7H16V9H3.83L9.41 14.59L8 16L0 8L8 0Z" />
                            </svg>
                        </div>
                        <div class="swiper-button-next controlBtn next">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0L9.41 1.41L3.83 7H16V9H3.83L9.41 14.59L8 16L0 8L8 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>