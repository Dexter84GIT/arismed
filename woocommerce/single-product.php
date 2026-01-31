<?php
defined('ABSPATH') || exit;
get_header();
$product = wc_get_product( get_the_ID() );
$article = get_field("article");
$conditions = get_field("conditions");
$list = get_field("list");
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
        <section class="card productCard section" itemscope itemtype="https://schema.org/Product">
            <div class="container df fdc gap30">
                <h1 class="sectionTitle druk" itemprop="name"><?php the_title(); ?></h1>
                <div class="content df fdc gap30">
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
                            <?php if ($article) : ?>
                                <div class="row df fdc gap10 article">
                                    <h3 class="druk" itemprop="brand">Артикул:</h3>
                                        <div class="field list">
                                            <?php echo $article; ?>
                                        </div>
                                    </div>
                            <?php endif ?>

                            <?php if ($conditions) : ?>
                                <?php include get_template_directory() . '/inc/page-checkout/parts/conditions.php'; ?> 
                            <?php endif ?>
                            <div class="row df aife controls gap20 jcsb" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <div class="price df fdc gap10">
                                    <p class="label">Цена:</p>
                                    <p class="value druk" itemprop="price">
                                        <?php echo esc_html( $product->get_regular_price() ); ?><span itemprop="priceCurrency">₽</span>
                                    </p>
                                    <link itemprop="availability" href="http://schema.org/InStock">
                                </div>

                            <!-- добавить в корзину -->
                                <div class="add df aife gap20">
                                    <form class="add df aic gap20 cart" data-product_id="<?php echo esc_attr($product->get_id()); ?>">
                                        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
                                        <?php include get_stylesheet_directory() . '/inc/page-checkout/parts/quantity.php'; ?>
                                        <?php include get_stylesheet_directory() . '/inc/page-checkout/parts/add-to-cart.php'; ?>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <span class="notice"></span>
                            </div>
                        </div>
                    </div>
                    <div class="block tabs df fdc gap10">
                        <div class="controls df aic gap10">
                            <?php if ($description) : ?>
                                <p class="tab active">Описание</p>
                            <?php endif ?>
                            <?php if ($list) : ?>
                                <p class="tab">Комплектация</p>
                            <?php endif ?>
                            <?php if ($file) : ?>
                                <p class="tab">Скачать сертификат</p>                    
                            <?php endif; ?>      
                        </div>
                        <?php if ($description) : ?>
                            <div class="tabContent block description df fdc gap30 active" itemprop="description">
                                <?php echo $description; ?>
                            </div>
                        <?php endif ?>
                        <?php if ($list) : ?>
                            <?php include get_template_directory() . '/inc/page-checkout/parts/complectation.php'; ?> 
                        <?php endif; ?>  
                        <?php if ($file) : ?>
                            <?php include get_template_directory() . '/inc/page-checkout/parts/certificat.php'; ?> 
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php get_footer(); ?>