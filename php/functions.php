<?php
/**
 * arismed functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package arismed
 */

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'arismed_setup' ) ) :
	function arismed_setup() {
		load_theme_textdomain( 'arismed', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'arismed' ),
			)
		);

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

		add_theme_support( 'customize-selective-refresh-widgets' );

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
endif;
add_action( 'after_setup_theme', 'arismed_setup' );

function arismed_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'arismed_content_width', 640 );
}
add_action( 'after_setup_theme', 'arismed_content_width', 0 );

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

function arismed_scripts() {
	wp_enqueue_style( 'arismed-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'arismed-style', 'rtl', 'replace' );

	wp_enqueue_script( 'arismed-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'arismed_scripts' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

add_action('wp_enqueue_scripts', function () {
    wp_deregister_style('woocommerce-general');
    wp_deregister_style('woocommerce-layout');
    wp_deregister_style('woocommerce-smallscreen');
    wp_deregister_style('woocommerce-inline');
    wp_deregister_style('select2');
    wp_deregister_style('woocommerce_prettyPhoto_css');
    wp_deregister_style('woocommerce_frontend_styles');
    wp_deregister_style('woocommerce-blocktheme');
    wp_deregister_style('wc-blocks-style');
    wp_deregister_style('wc-blocks-vendors-style');
}, 100);

add_action('after_setup_theme', function () {
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
}, 100);

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
        'cart_nonce' => wp_create_nonce('arismed_cart'),
        'endpoints' => [
            'mini_cart' => 'arismed_mini_cart',
            'sync' => 'arismed_cart_sync',
            'remove' => 'arismed_cart_remove',
            'update_qty' => 'arismed_cart_update_qty',
        ],
    ]);
}, 110);

add_action('wc_ajax_arismed_mini_cart', 'arismed_mini_cart');
add_action('wc_ajax_nopriv_arismed_mini_cart', 'arismed_mini_cart');

add_action('wc_ajax_arismed_cart_sync', 'arismed_cart_sync');
add_action('wc_ajax_nopriv_arismed_cart_sync', 'arismed_cart_sync');

add_action('wc_ajax_arismed_cart_remove', 'arismed_cart_remove');
add_action('wc_ajax_nopriv_arismed_cart_remove', 'arismed_cart_remove');

add_action('wc_ajax_arismed_cart_update_qty', 'arismed_cart_update_qty');
add_action('wc_ajax_nopriv_arismed_cart_update_qty', 'arismed_cart_update_qty');

function arismed_cart_ensure_loaded() {
    if (!function_exists('WC')) return false;

    if (function_exists('wc_load_cart') && (null === WC()->cart || !WC()->cart)) {
        wc_load_cart();
    }

    if (!WC()->cart) return false;

    WC()->cart->calculate_totals();
    return true;
}

function arismed_cart_nonce_ok($nonce) {
    $nonce = is_string($nonce) ? $nonce : '';
    return $nonce && wp_verify_nonce($nonce, 'arismed_cart');
}

function arismed_mini_cart() {
    if (!arismed_cart_ensure_loaded()) {
        wp_send_json_error(['message' => 'Cart unavailable'], 400);
    }

    ob_start();
    include get_stylesheet_directory() . '/inc/shared/miniCart.php';
    $html = ob_get_clean();

    wp_send_json([
        'ok' => true,
        'html' => $html,
        'count' => (int) WC()->cart->get_cart_contents_count(),
    ]);
}

function arismed_cart_payload() {
    ob_start();
    arismed_render_mini_cart_items();
    $items = ob_get_clean();

    ob_start();
    arismed_render_mini_cart_total();
    $total = ob_get_clean();

    ob_start();
    arismed_render_mini_cart_count();
    $count_html = ob_get_clean();

    ob_start();
    arismed_render_cart_page_items();
    $cart_page_items_html = ob_get_clean();

    ob_start();
    arismed_render_cart_page_total();
    $cart_page_total_html = ob_get_clean();

    return [
        'ok' => true,
        'count' => (int) WC()->cart->get_cart_contents_count(),
        'count_html' => $count_html,
        'items_html' => $items,
        'total_html' => $total,
        'cart_page_items_html' => $cart_page_items_html,
        'cart_page_total_html' => $cart_page_total_html,
        'cart_hash' => WC()->cart->get_cart_hash(),
    ];
}

function arismed_render_cart_page_items() {
    $cart = WC()->cart;
    if (!$cart) return;

    $items = $cart->get_cart();
    $currency = get_woocommerce_currency_symbol();
    $decimals = (int) wc_get_price_decimals();

    $fmt_price = function ($v) use ($decimals) {
        $v = (float) $v;
        $use_decimals = $decimals > 0 && abs($v - round($v)) > 0.000001;
        return number_format_i18n($v, $use_decimals ? $decimals : 0);
    };

    if (!$items) {
        ?>
        <div class="row unit df aic gap60">
            <div class="block df aic gap30">
                <p class="name druk">Корзина пуста</p>
            </div>
        </div>
        <?php
        return;
    }

    foreach ($items as $cart_item_key => $cart_item) {
        $product = $cart_item['data'] ?? null;
        if (!$product || !$product->exists()) continue;

        $qty = (int) ($cart_item['quantity'] ?? 0);
        if ($qty <= 0) continue;

        $name = $product->get_name();

        $img_id = (int) $product->get_image_id();
        $img = $img_id ? wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail') : '';

        $product_id = (int) ($cart_item['product_id'] ?? 0);
        $link = $product_id ? get_permalink($product_id) : '';

        $price_now = (float) wc_get_price_to_display($product, ['qty' => 1]);
        $price_old = 0.0;

        if ($product->is_on_sale()) {
            $regular = (float) wc_get_price_to_display($product, ['qty' => 1, 'price' => $product->get_regular_price()]);
            if ($regular > 0 && $regular > $price_now) $price_old = $regular;
        }
        ?>
        <div class="row unit df aic gap60" data-key="<?php echo esc_attr($cart_item_key); ?>">
            <div class="block df aic gap30">
                <div class="img">
                    <?php if ($img) : ?>
                        <?php if ($link) : ?>
                            <a href="<?php echo esc_url($link); ?>">
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                            </a>
                        <?php else : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if ($link) : ?>
                    <a class="name druk" href="<?php echo esc_url($link); ?>"><?php echo esc_html($name); ?></a>
                <?php else : ?>
                    <p class="name druk"><?php echo esc_html($name); ?></p>
                <?php endif; ?>
            </div>

            <div class="block df aic gap30">
                <?php if ($price_old > 0) : ?>
                    <p class="old"><?php echo esc_html($fmt_price($price_old)); ?><span class="currency"><?php echo esc_html($currency); ?></span></p>
                <?php endif; ?>
                <p class="new druk"><?php echo esc_html($fmt_price($price_now)); ?><span class="currency"><?php echo esc_html($currency); ?></span></p>
            </div>

            <div class="quantity df aic gap20">
                <div class="dec btn" data-action="dec" role="button" tabindex="0" aria-label="Уменьшить">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2498 9.74854H3.74976V8.24854H14.2498V9.74854Z" />
                    </svg>
                </div>
                <p class="count" data-count><?php echo (int) $qty; ?></p>
                <div class="inc btn" data-action="inc" role="button" tabindex="0" aria-label="Увеличить">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2498 9.74976H9.74976V14.2498H8.24976V9.74976H3.74976V8.24976H8.24976V3.74976H9.74976V8.24976H14.2498V9.74976Z" />
                    </svg>
                </div>
            </div>

            <p class="cancel df aic jcc" data-action="remove" role="button" tabindex="0" aria-label="Удалить">
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" />
                </svg>
            </p>
        </div>
        <?php
    }
}

function arismed_render_cart_page_total() {
    if (!WC()->cart) return;
    $total = (float) WC()->cart->get_total('edit');
    $currency = get_woocommerce_currency_symbol();
    echo esc_html(number_format_i18n($total, 0)) . '<span class="currency">' . esc_html($currency) . '</span>';
}


function arismed_cart_sync() {
    if (!arismed_cart_ensure_loaded()) {
        wp_send_json_error(['message' => 'Cart unavailable'], 400);
    }

    wp_send_json(arismed_cart_payload());
}

function arismed_cart_remove() {
    if (!arismed_cart_ensure_loaded()) {
        wp_send_json_error(['message' => 'Cart unavailable'], 400);
    }

    $nonce = isset($_POST['nonce']) ? sanitize_text_field((string) $_POST['nonce']) : '';
    if (!arismed_cart_nonce_ok($nonce)) {
        wp_send_json_error(['message' => 'Bad nonce'], 403);
    }

    $key = isset($_POST['key']) ? wc_clean((string) $_POST['key']) : '';
    if (!$key) {
        wp_send_json_error(['message' => 'No key'], 400);
    }

    WC()->cart->remove_cart_item($key);
    WC()->cart->calculate_totals();

    wp_send_json(arismed_cart_payload());
}

function arismed_cart_update_qty() {
    if (!arismed_cart_ensure_loaded()) {
        wp_send_json_error(['message' => 'Cart unavailable'], 400);
    }

    $nonce = isset($_POST['nonce']) ? sanitize_text_field((string) $_POST['nonce']) : '';
    if (!arismed_cart_nonce_ok($nonce)) {
        wp_send_json_error(['message' => 'Bad nonce'], 403);
    }

    $key = isset($_POST['key']) ? wc_clean((string) $_POST['key']) : '';
    $qty = isset($_POST['qty']) ? (int) $_POST['qty'] : 1;
    $qty = max(1, $qty);

    if (!$key || !WC()->cart->get_cart_item($key)) {
        wp_send_json_error(['message' => 'Bad key'], 400);
    }

    WC()->cart->set_quantity($key, $qty, true);
    WC()->cart->calculate_totals();

    wp_send_json(arismed_cart_payload());
}

function arismed_render_mini_cart_count() {
    $count = WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
    echo (int) $count;
}

function arismed_render_mini_cart_items() {
    $cart = WC()->cart;
    if (!$cart) return;

    $currency = get_woocommerce_currency_symbol();
    $nonce = wp_create_nonce('arismed_cart');

    if ($cart->is_empty()) {
        echo '<p class="empty">Корзина пуста</p>';
        return;
    }

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'] ?? null;
        if (!$product || !$product->exists()) continue;

        $qty = (int) ($cart_item['quantity'] ?? 0);
        if ($qty <= 0) continue;

        $name = $product->get_name();
        $img_id = (int) $product->get_image_id();
        $img = $img_id ? wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail') : '';

        $unit = (float) wc_get_price_to_display($product, ['qty' => 1]);
        $decimals = (int) wc_get_price_decimals();
        $use_decimals = $decimals > 0 && abs($unit - round($unit)) > 0.000001;
        $unit_str = number_format_i18n($unit, $use_decimals ? $decimals : 0);
        ?>
        <div class="item df aic gap20" data-key="<?php echo esc_attr($cart_item_key); ?>">
            <div class="img">
                <?php if ($img) : ?>
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                <?php endif; ?>
            </div>

            <div class="text df fdc gap10">
                <p class="name"><?php echo esc_html($name); ?></p>
                <p class="cost druk">
                    <span class="number"><?php echo esc_html((string) $qty); ?></span>
                    <span>x</span>
                    <span class="sum"><?php echo esc_html($unit_str); ?><span class="currency"><?php echo esc_html($currency); ?></span></span>
                </p>
            </div>

            <button class="delete" type="button" aria-label="Удалить" data-nonce="<?php echo esc_attr($nonce); ?>">
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" />
                </svg>
            </button>
        </div>
        <?php
    }
}

function arismed_render_mini_cart_total() {
    if (!WC()->cart) return;

    $total = (float) WC()->cart->get_total('edit');
    $currency = get_woocommerce_currency_symbol();
    echo esc_html(number_format_i18n($total, 0)) . '<span class="currency">' . esc_html($currency) . '</span>';
}

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

add_action('init', function () {
    remove_post_type_support('product', 'editor');
    remove_post_type_support('product', 'excerpt');
});

// перенаправление ссылок для каталога

$catalog_links = get_template_directory() . '/functions/breadcrumbs_links.php';
if (file_exists($catalog_links)) {
    require_once $catalog_links;
}

// вставка линка на магазин в хлебные крошки

$breadcrumbs_links = get_template_directory() . '/functions/breadcrumbs_links.php';
if (file_exists($breadcrumbs_links)) {
    require_once $breadcrumbs_links;
}

// чекаут

$checkout_function = get_template_directory() . '/functions/checkout.php';
if (file_exists($checkout_function)) {
    require_once $checkout_function;
}

// биллинг

$billing_form = get_template_directory() . '/functions/billing_form.php';
if (file_exists($billing_form)) {
    require_once $billing_form;
}

// регистрация

$getRegister = get_template_directory() . '/functions/register.php';
if (file_exists($getRegister)) {
    require_once $getRegister;
}

// логин

$getLogin = get_template_directory() . '/functions/login.php';
if (file_exists($getLogin)) {
    require_once $getLogin;
}

// поиск в шапке

$headerSearch = get_template_directory() . '/functions/header_search.php';
if (file_exists($headerSearch)) {
    require_once $headerSearch;
}

// поиск в документах

$docsSearch = get_template_directory() . '/functions/docs_search.php';
if (file_exists($docsSearch)) {
    require_once $docsSearch;
}

// симуляция платежа

$fakePay = get_template_directory() . '/functions/checkout_submit.php';
if (file_exists($fakePay)) {
    require_once $fakePay;
}

// проверка полей пользователя на чекауте

$checkoutUserFields = get_template_directory() . '/functions/checkout_user_fields.php';
if (file_exists($checkoutUserFields)) {
    require_once $checkoutUserFields;
}

// парсинг сохраненных адресов

$checkoutAdress = get_template_directory() . '/functions/checkout_adress.php';
if (file_exists($checkoutAdress)) {
    require_once $checkoutAdress;
}

// сохранение адресов в мету

$accountAddAdress = get_template_directory() . '/functions/account_add_adress.php';
if (file_exists($accountAddAdress)) {
    require_once $accountAddAdress;
}

// удаление адреса из меты

$accountDeleteAdress = get_template_directory() . '/functions/account_delete_adress.php';
if (file_exists($accountDeleteAdress)) {
    require_once $accountDeleteAdress;
}

// принудительно переключаем шаблон категорий

add_filter('template_include', function ($template) {
    if (function_exists('is_product_category') && is_product_category()) {
        $t = get_template_directory() . '/taxonomy-product_cat.php';
        if (is_readable($t)) return $t;
    }
    if (function_exists('is_shop') && is_shop()) {
        $t = get_template_directory() . '/archive-product.php';
        if (is_readable($t)) return $t;
    }
    return $template;
}, 99999);

// опускаем yoast вниз

add_filter('wpseo_metabox_prio', function () {
    return 'low';
});

// отключаем yoast seo для некоторых типов

add_action('add_meta_boxes', function () {
    $disable = [
        'company_docs',
    ];

    foreach ($disable as $post_type) {
        remove_meta_box('wpseo_meta', $post_type, 'normal');
    }
}, 100);
