<?php
/*
  Template Name: Формирование счета
*/
defined('ABSPATH') || exit;

get_header();

/**
 * ЖЁСТКАЯ инициализация Woo
 * Без этого WC()->session может быть null
 */
if (function_exists('WC')) {
    WC()->frontend_includes();
    WC()->initialize_session();
    WC()->initialize_cart();
}

/**
 * Проверка авторизации
 */
if (!is_user_logged_in()) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Требуется авторизация</h2></div></section>';
    get_footer();
    exit;
}

/**
 * Проверка intent
 */
$intent = isset($_GET['intent'])
    ? sanitize_text_field(wp_unslash($_GET['intent']))
    : '';

if (!$intent) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Некорректный запрос</h2></div></section>';
    get_footer();
    exit;
}

/**
 * Проверка Woo session
 */
if (!WC()->session) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Сессия WooCommerce недоступна</h2></div></section>';
    get_footer();
    exit;
}

/**
 * Проверка intent-сессии
 */
$session_key = "arismed_intent_$intent";
$data = WC()->session->get($session_key);

$user_id = get_current_user_id();

if (
    !$data ||
    empty($data['expires']) ||
    $data['expires'] < time() ||
    (int) $data['user_id'] !== $user_id
) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Сессия истекла или недействительна</h2></div></section>';
    get_footer();
    exit;
}

/**
 * Лимит счетов
 */
if (!arismed_can_create_invoice($user_id)) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Превышено количество запросов счета</h2></div></section>';
    get_footer();
    exit;
}

/**
 * Создание заказа
 */
$order = wc_create_order();

if (is_wp_error($order)) {
    echo '<section class="invoice section"><div class="container"><h2 class="pageTitle">Не удалось создать заказ</h2></div></section>';
    get_footer();
    exit;
}

/**
 * Перенос товаров из корзины
 */
foreach (WC()->cart->get_cart() as $item) {
    $product = wc_get_product($item['product_id']);
    if ($product) {
        $order->add_product($product, $item['quantity']);
    }
}

/**
 * Финализация заказа
 */
$order->set_customer_id($user_id);
$order->set_payment_method('invoice');
$order->set_payment_method_title('Счет');
$order->set_status('pending');

$order->calculate_totals();
$order->save();

/**
 * Учёт лимита + очистка
 */
arismed_inc_invoice_counter($user_id);

WC()->session->__unset($session_key);
WC()->cart->empty_cart();
?>

<section class="invoice section">
    <div class="container df fdc gap20">
        <?php include get_template_directory() . '/inc/page-checkout/invoice.php'; ?>
    </div>
</section>

<?php get_footer(); ?>