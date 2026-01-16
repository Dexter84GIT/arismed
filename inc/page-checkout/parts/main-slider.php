<div class="swiper main slider cardMainSlider">
    <div class="swiper-wrapper">
        <?php foreach ($images as $id) :
            $full = wp_get_attachment_image_url($id, 'full');
            $img  = wp_get_attachment_image($id, 'large', false, ['alt' => get_the_title()]);
            if (!$img) continue;
        ?>
            <div class="swiper-slide slide" data-full="<?php echo esc_url($full ?: ''); ?>">
                <?php echo $img; ?>
                <meta itemprop="image" content="<?php echo esc_url($full); ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>