<?php
defined('ABSPATH') || exit;

if (!function_exists('WC')) {
    return;
}

$intent = sanitize_text_field($_GET['intent'] ?? '');
$session = WC()->session->get("arismed_intent_$intent");

if (
    !$intent ||
    !is_array($session) ||
    ($session['mode'] ?? null) !== 'online' ||
    ($session['expires'] ?? 0) < time()
) {
    return;
}

$cart = WC()->cart;
if (!$cart || $cart->is_empty()) {
    return;
}

$data = $session['data'] ?? [];

if (!is_array($data)) {
    $data = [];
}

if (empty($session['order_id'])) {
    $order = wc_create_order();

    foreach ($cart->get_cart() as $item) {
        $order->add_product(
            $item['data'],
            $item['quantity']
        );
    }

    foreach ($data as $key => $value) {
        if ($value !== '') {
            $order->update_meta_data($key, $value);
        }
    }

    $order->set_payment_method('yookassa');
    $order->set_payment_method_title('Онлайн-оплата');

    $order->set_status('pending');
    $order->calculate_totals();
    $order->save();

    WC()->session->set(
        "arismed_intent_$intent",
        array_merge($session, [
            'order_id' => $order->get_id(),
        ])
    );
} else {
    $order = wc_get_order((int) $session['order_id']);
    if (!$order) {
        return;
    }
}

if (empty($session['confirmation_token'])) {
    if (!defined('YOOKASSA_SHOP_ID') || !defined('YOOKASSA_SECRET_KEY')) {
        error_log('YooKassa constants not defined');
        return;
    }

    $total = wc_format_decimal($order->get_total(), 2);

    $client = new \YooKassa\Client();
    $client->setAuth(
        YOOKASSA_SHOP_ID,
        YOOKASSA_SECRET_KEY
    );

    $payment = $client->createPayment([
        'amount' => [
            'value' => $total,
            'currency' => 'RUB',
        ],
        'confirmation' => [
            'type' => 'embedded',
        ],
        'capture' => true,
        'description' => 'Оплата заказа #' . $order->get_order_number(),
        'metadata' => [
            'order_id' => $order->get_id(),
        ],
    ], $intent);

    $confirmation_token = $payment
        ->getConfirmation()
        ->getConfirmationToken();

    $order->update_meta_data('_yookassa_payment_id', $payment->getId());
    $order->save();

    WC()->session->set(
        "arismed_intent_$intent",
        array_merge(
            WC()->session->get("arismed_intent_$intent"),
            [
                'payment_id' => $payment->getId(),
                'confirmation_token' => $confirmation_token,
            ]
        )
    );
} else {
    $confirmation_token = $session['confirmation_token'];
}
?>

<script>
    window.ARISMED_CONFIRMATION_TOKEN = "<?= esc_js($confirmation_token) ?>";
    window.ARISMED_ORDER_ID = <?= (int) $order->get_id() ?>;
    window.ARISMED_ORDER_KEY = "<?= esc_js($order->get_order_key()) ?>";
</script>

<div class="block df aic jcc">
    <div id="payment-form"></div>
</div>