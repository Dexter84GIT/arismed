        <section class="mainNews section">
            <div class="patterns bg">
                <div class="pattern_001">
                    <img src="<?php bloginfo('template_directory'); ?>/img/plus_002.png" alt="plus">
                </div>
                <div class="pattern_002">
                    <img src="<?php bloginfo('template_directory'); ?>/img/plus_001.png" alt="plus">
                </div>
            </div>
            <div class="wrap">
                <div class="container df fdc gap30">
                    <div class="top df aic jcsb">
                        <h2 class="sectionTitle druk">Новости</h2>
                        <a href="/news" class="link druk">Все новости</a>
                    </div>
                    <div class="content swiper slider slider3 newsSlider">
                        <div class="swiper-wrapper">
                            <?php
                            $q = new WP_Query([
                                'post_type' => 'company_news',
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
                            
                                <a href="<?php the_permalink(); ?>" class="swiper-slide slide df fdc">
                                    <div class="img">
                                        <img src="<?php echo esc_url($image); ?>" alt="news">
                                    </div>
                                    <div class="text df fdc gap20">
                                        <p class="date"><?php echo get_the_date('n-j-Y'); ?></p>
                                        <p class="title druk"><?php the_title();?></p>
                                    </div>
                                </a>
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
                    <a href="/news" class="seeMore mobile">Все новости</a>
                </div>
            </div>
        </section>