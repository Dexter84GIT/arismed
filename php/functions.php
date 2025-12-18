<?php
/**
 * arismed functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package arismed
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function arismed_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on arismed, use a find and replace
		* to change 'arismed' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'arismed', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'arismed' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'arismed_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'arismed_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function arismed_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'arismed_content_width', 640 );
}
add_action( 'after_setup_theme', 'arismed_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function arismed_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'arismed' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'arismed' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'arismed_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function arismed_scripts() {
	wp_enqueue_style( 'arismed-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'arismed-style', 'rtl', 'replace' );

	wp_enqueue_script( 'arismed-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'arismed_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/dist/style.css', ['parent-style']);
}, 20);

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'arismed-main',
        get_stylesheet_directory_uri() . '/dist/main.js',
        [],
        filemtime(get_stylesheet_directory() . '/dist/main.js'),
        true
    );

    wp_localize_script('arismed-main', 'ARISMED', [
        'wc_ajax' => home_url('/?wc-ajax='),
    ]);
}, 110);

// мини-корзина

add_action('wc_ajax_arismed_mini_cart', 'arismed_mini_cart');
add_action('wc_ajax_nopriv_arismed_mini_cart', 'arismed_mini_cart');

function arismed_mini_cart() {
    if (function_exists('wc_load_cart') && null === WC()->cart) {
        wc_load_cart();
    }

    if (WC()->cart) {
        WC()->cart->calculate_totals();
    }

    ob_start();
    include get_stylesheet_directory() . '/inc/shared/miniCart.php';
    $html = ob_get_clean();

    wp_send_json([
        'html' => $html,
        'count' => WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0,
    ]);
}

// удаление позиции из заказа

add_action('wc_ajax_arismed_remove_from_cart', 'arismed_remove_from_cart');
add_action('wc_ajax_nopriv_arismed_remove_from_cart', 'arismed_remove_from_cart');

function arismed_remove_from_cart() {
    if (!function_exists('WC') || !WC()->cart) {
        wp_send_json_error(['message' => 'Cart unavailable'], 400);
    }

    $nonce = isset($_POST['nonce']) ? sanitize_text_field((string) $_POST['nonce']) : '';
    if (!$nonce || !wp_verify_nonce($nonce, 'arismed_cart')) {
        wp_send_json_error(['message' => 'Bad nonce'], 403);
    }

    $key = isset($_POST['key']) ? wc_clean((string) $_POST['key']) : '';
    if (!$key) {
        wp_send_json_error(['message' => 'No key'], 400);
    }

    WC()->cart->remove_cart_item($key);
    WC()->cart->calculate_totals();

    WC_AJAX::get_refreshed_fragments();
}

// AJAX для одиночного продукта

add_filter('woocommerce_product_single_add_to_cart_text', '__return_false');
add_filter('woocommerce_product_supports', function ($supports, $feature, $product) {
    if ($feature === 'ajax_add_to_cart') {
        $supports = true;
    }
    return $supports;
}, 10, 3);

add_action('wp_enqueue_scripts', function () {
    if (is_product()) {
        wp_enqueue_script('wc-add-to-cart');
    }
});

// убрать tiny MCE при создании товара

add_action('init', function () {
    remove_post_type_support('product', 'editor');
    remove_post_type_support('product', 'excerpt');
});

// шорткод корзины

add_shortcode('arismed_cart', function () {
    if (!function_exists('WC') || !WC()->cart) return '';

    ob_start();

    $cart = WC()->cart;
    ?>
    <form method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>" class="woocommerce-cart-form">
    <section class="cart section">
        <div class="container df fdc gap30">
            <h2 class="sectionTitle druk">Корзина</h2>

            <div class="content df fdc gap10">
                <?php if ($cart->is_empty()) : ?>
                    <p class="empty">Сейчас ваша корзина пуста!</p>
                <?php else : ?>
                    <?php foreach ($cart->get_cart() as $key => $item) :
                        $p = $item['data'] ?? null;
                        if (!$p || !$p->exists()) continue;

                        $qty = (int) ($item['quantity'] ?? 0);
                        if ($qty <= 0) continue;

                        $name = $p->get_name();
                        $img = $p->get_image('woocommerce_thumbnail');
                        $regular = (float) $p->get_regular_price();
                        $sale    = (float) $p->get_sale_price();

                        $price_regular_html = $regular > 0
                            ? wc_price(wc_get_price_to_display($p, ['price' => $regular]))
                            : '';

                        $price_sale_html = ($sale > 0 && $sale < $regular)
                            ? wc_price(wc_get_price_to_display($p, ['price' => $sale]))
                            : '';
                        $remove = wc_get_cart_remove_url($key);
                        ?>
                        <div class="row unit df aic gap60" data-key="<?php echo esc_attr($key); ?>">
                            <div class="block df aic gap30">
                                <div class="img"><?php echo $img; ?></div>
                                <p class="name"><?php echo esc_html($name); ?></p>
                            </div>
                            
                            <div class="block df aic gap30 prices">
                                <?php if ($price_sale_html) : ?>
                                    <p class="old"><?php echo $price_regular_html; ?></p>
                                    <p class="new"><?php echo $price_sale_html; ?></p>
                                <?php else : ?>
                                    <p class="new"><?php echo $price_regular_html; ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="quantity df aic gap20" data-key="<?php echo esc_attr($key); ?>">
                                <div class="dec btn">-</div>
                                <p class="count"><?php echo esc_html((string)$qty); ?></p>
                                <div class="inc btn">+</div>

                                <input
                                    type="number"
                                    class="qtyInput"
                                    name="<?php echo esc_attr("cart[{$key}][qty]"); ?>"
                                    value="<?php echo esc_attr((string)$qty); ?>"
                                    min="1"
                                    step="1"
                                    inputmode="numeric"
                                    style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;"
                                >
                            </div>

                            <a class="cancel df aic jcc" href="<?php echo esc_url($remove); ?>">×</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="controls df fdc gap40">
                <p class="legend overall df aic gap20">
                    Итого:
                    <span class="sum"><?php echo wp_kses_post($cart->get_cart_total()); ?></span>
                </p>
                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="toCheckout btn df aic gap10" id="toCheckout">
                    <span class="druk">Перейти к оформлению заказа</span>
                </a>
            </div>
        </div>
    </section>
    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
    <button type="submit" name="update_cart" value="1" style="display:none;"></button>
    </form>
    <?php

    return ob_get_clean();
});
