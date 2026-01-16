        <section class="sales info section">
            <div class="container df fdc gap30">
                    <h2 class="sectionTitle druk">Акции</h2>
                    <div class="content df fww ais gap30">
                <?php
                    $q = new WP_Query([
                        'post_type' => 'company_sales',
                        'post_status' => 'publish',
                        'posts_per_page' => -99,
                        'no_found_rows' => true,
                        'ignore_sticky_posts' => true,
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
                        $image = get_field('image'); 
                ?>

                <a href="<?php the_permalink(); ?>" class="sale item df fdc">
                    <div class="img">
                        <img src="<?php echo esc_url($image); ?>" alt="news">
                    </div>
                    <div class="row text">
                        <p class="title druk"><?php the_title(); ?></p>
                    </div>
                </a>

                <?php
                    endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
                </div>
            </div>
        </section>

        