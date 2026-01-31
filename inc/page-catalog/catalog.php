        <h2 class="sectionTitle druk">Каталог</h2> 
              <div class="content df fww ais gap20 catalog">
                <?php
                $cats = get_terms([
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => 0,
                    'orderby' => 'name',
                    'order' => 'ASC',
                ]);
                
                if (!is_wp_error($cats) && $cats) :
                    foreach ($cats as $cat) :
                        $link = get_term_link($cat);
                        if (is_wp_error($link)) continue;
                    
                        $thumb_id = (int) get_term_meta($cat->term_id, 'thumbnail_id', true);
                        $img = $thumb_id ? wp_get_attachment_image($thumb_id, 'woocommerce_thumbnail', false, ['alt' => $cat->name]) : '';
                        if (!$img) {
                            $img = '<img src="' . esc_url(get_template_directory_uri() . '/img/catalog_001.png') . '" alt="' . esc_attr($cat->name) . '">';
                        }
                ?>
                    <a href="<?php echo esc_url($link); ?>" class="item df fdc gap20">
                        <div class="img">
                            <?php echo $img; ?>
                        </div>
                        <p class="title druk"><?php echo esc_html($cat->name); ?></p>
                        <div class="btn df aic gap5 redBtn">
                            <p>смотреть</p>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.72665 11.06L8.77999 8L5.72665 4.94L6.66665 4L10.6667 8L6.66665 12L5.72665 11.06Z" />
                            </svg>
                        </div>
                    </a>
                <?php
                    endforeach;
                endif;
                ?>
                </div>