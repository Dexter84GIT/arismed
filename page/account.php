<?php
/*
 Template name: Личный кабинет
 */
if (!is_user_logged_in()) {
    wp_safe_redirect(home_url('/login/'));
    exit;
}
get_header(); ?>

<section class="account section">
    <div class="container df fdc gap60 tabs">
        <?php include get_template_directory() . '/inc/page-account/controls.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/orders.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/adress.php'; ?>
        <?php include get_template_directory() . '/inc/page-account/data.php'; ?>
    </div>
</section>

<?php get_footer(); ?>