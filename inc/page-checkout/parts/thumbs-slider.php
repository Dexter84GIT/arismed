<div class="swiper thumbs slider cardThumbsSlider">
    <div class="swiper-wrapper">
        <?php foreach ($images as $id) :
            $thumb = wp_get_attachment_image_url($id, 'woocommerce_gallery_thumbnail');
            if (!$thumb) $thumb = wp_get_attachment_image_url($id, 'thumbnail');
            $full = wp_get_attachment_image_url($id, 'full') ?: $thumb;
            if (!$thumb) continue;
        ?>
            <div class="swiper-slide slide">
                <img src="<?php echo esc_url($thumb); ?>" data-full="<?php echo esc_url($full); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>