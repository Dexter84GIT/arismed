<?php
/*
 Template name: Страница каталога
 */
get_header(); 
$is_shop = function_exists('is_shop') && is_shop();
$is_cat = function_exists('is_product_category') && is_product_category();

?>
<section class="pageCatalog catalog section mainGoods">
    <div class="container df fdc gap30">
        <?php
        if ($is_cat) {
            include get_template_directory() . '/inc/page-catalog/category.php';
        } else {
            include get_template_directory() . '/inc/page-catalog/catalog.php';
        }
        ?>
    </div>
</section>
<?php get_footer(); ?>