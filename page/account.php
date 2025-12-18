<?php
/*
 Template name: Личный кабинет
 */
get_header(); ?>

<section class="account section">
    <div class="container df fdc gap60 tabs">
        <?php include get_template_directory() . '/inc/page-account/controls.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/orders.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/docs.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/adress.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/data.php'; ?>
    </div>
</section>

<?php get_footer(); ?>