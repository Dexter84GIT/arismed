<?php
defined('ABSPATH') || exit;
get_header();
$product = wc_get_product( get_the_ID() );
$article = get_field("article");
$complectation = get_field("complectation");
$conditions = get_field("conditions");
$description = get_field("description");
$file = get_field('sertifikat');  
$main_img = $product->get_image_id(); 
$gallery_ids = $product->get_gallery_image_ids();

$images = [];
if ($main_img) $images[] = (int) $main_img;

if ($gallery_ids) {
    foreach ($gallery_ids as $id) {
        $id = (int) $id;
        if ($id && (! $main_img || $id !== (int) $main_img)) $images[] = $id;
    }
}         
?> 
        <section class="card productCard section">
            <div class="container df fdc gap30">
                <h2 class="sectionTitle druk"><?php the_title(); ?></h2>
                <div class="content df fdc gap60">
                    <div class="block info df aifs gap30">
                        <div class="column df fdc gap30">
                            <?php if ($main_img) : 
                                 include get_template_directory() . '/inc/page-checkout/parts/main-slider.php';  
                            else : ?>
                                <div class="noimg df aic jcc">

                                </div>
                            <?php endif; ?>

                            <?php if ($main_img) : ?>
                                <?php include get_template_directory() . '/inc/page-checkout/parts/thumbs-slider.php'; ?> 
                            <?php endif; ?>
                        </div>
                        <div class="column df fdc gap30">
                            <?php if ($complectation) : ?>
                                <?php include get_template_directory() . '/inc/page-checkout/parts/complectation.php'; ?> 
                            <?php endif ?>
                            <?php if ($conditions) : ?>
                                <?php include get_template_directory() . '/inc/page-checkout/parts/conditions.php'; ?> 
                            <?php endif ?>
                            <div class="row df aife controls gap20 jcsb">
                                <div class="price df fdc gap10">
                                    <p class="label">Цена:</p>
                                    <p class="value druk">
                                        <?php echo esc_html( $product->get_regular_price() ); ?><span>₽</span>
                                    </p>
                                </div>

                            <!-- добавить в корзину -->
                                <div class="add df aic gap20">
                                    <form class="add df aic gap20 cart" method="post" data-product_id="<?php echo esc_attr($product->get_id()); ?>">
                                        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <?php include get_stylesheet_directory() . '/inc/page-checkout/parts/quantity.php'; ?>
                                        <?php include get_stylesheet_directory() . '/inc/page-checkout/parts/add-to-cart.php'; ?>
                                    </form>
                                    <span class="notice"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- описание -->
                    <?php if ($description) : ?>
                    <div class="block description df fdc gap30">
                        <h2 class="sectionTitle druk">Описание</h2>
                        <?php echo $description; ?>
                    </div>
                    <?php endif ?>
                    <!-- сертификат -->
                    <?php if ($file) : ?>
                        <?php include get_template_directory() . '/inc/page-checkout/parts/certificat.php'; ?> 
                    <?php endif; ?>           
                </div>

            </div>
        </section>
        <?php get_footer(); ?>