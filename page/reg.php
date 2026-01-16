<?php  
/*   
    Template name: Регистрация   
*/  
defined('ABSPATH') || exit;

if (is_user_logged_in()) {
    wp_safe_redirect(home_url('/account/'));
    exit;
}

get_header(); ?>
      
    <?php include get_template_directory() . '/inc/page-account/reg.php'; ?>

<?php get_footer(); ?>