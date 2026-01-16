<section class="news info section df fdc gap60">
    <div class="container df fdc gap60">
        <div class="content df ais fww gap20">
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
            
                <a href="<?php the_permalink(); ?>" class="df fdc item">
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
    </div>
</section>