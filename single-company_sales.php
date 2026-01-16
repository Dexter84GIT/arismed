<?php
get_header();
?>
<section class="singleNews section">
  <div class="container df fdc gap30">
    <?php 
        $image = get_field('image');
        $content = get_field('content');
    ?>
    <h2 class="sectionTitle"><?php the_title(); ?></h2>
    <div class="img">
        <img src="<?php echo esc_url($image); ?>" alt="image">
    </div>
    <p class="date"><?php echo get_the_date('n-j-Y'); ?></p>
    <?php echo $content; ?></p>
  </div>
</section>
<?php
get_footer();