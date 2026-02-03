<?php
/*
  Template Name: Формирование счета
*/
defined('ABSPATH') || exit;

get_header();

if (function_exists('WC')) {
    WC()->frontend_includes();
    WC()->initialize_session();
    WC()->initialize_cart();
}

if (!is_user_logged_in()) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Требуется авторизация</h2></div></section>';
    get_footer();
    exit;
}

$intent = isset($_GET['intent'])
    ? sanitize_text_field(wp_unslash($_GET['intent']))
    : '';

if (!$intent) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Некорректный запрос</h2></div></section>';
    get_footer();
    exit;
}

if (!WC()->session) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Сессия WooCommerce недоступна</h2></div></section>';
    get_footer();
    exit;
}

$session_key = "arismed_intent_$intent";
$session = WC()->session->get($session_key);

$user_id = get_current_user_id();

if (
    !$session ||
    empty($session['expires']) ||
    $session['expires'] < time() ||
    (int) $session['user_id'] !== $user_id ||
    ($session['mode'] ?? '') !== 'invoice'
) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Сессия истекла или недействительна</h2></div></section>';
    get_footer();
    exit;
}

if (!arismed_can_create_invoice($user_id)) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Превышено количество запросов счета</h2></div></section>';
    get_footer();
    exit;
}

$checkout = $session['checkout'] ?? [];
$cart_snapshot = $session['cart'] ?? [];
?>

<section class="invoice section" id="invoice-prepare">
    <div class="container df fdc gap20">
        <?php
        include get_template_directory() . '/inc/page-checkout/invoice.php';
        ?>
    </div>
</section>

<?php get_footer(); ?>
