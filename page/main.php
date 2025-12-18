<?php
/*
 Template name: Главная страница
 */
get_header('main'); ?>

    <?php include get_template_directory() . '/inc/page-main/hero.php'; ?>
    <?php include get_template_directory() . '/inc/page-main/supremacy.php'; ?>
    <?php include get_template_directory() . '/inc/page-main/catalog.php'; ?>
    <?php include get_template_directory() . '/inc/page-main/goods.php'; ?>
    <?php include get_template_directory() . '/inc/page-main/news.php'; ?>
    <?php include get_template_directory() . '/inc/page-main/faq.php'; ?>
    <?php include get_template_directory() . '/inc/page-main/about.php'; ?>
    
<?php get_footer(); ?>