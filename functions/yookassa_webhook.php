<?php
add_action('rest_api_init', function () {
    register_rest_route('arismed/v1', '/yookassa-webhook', [
        'methods'  => 'POST',
        'callback' => 'arismed_yookassa_webhook',
        'permission_callback' => '__return_true',
    ]);
});

function arismed_yookassa_webhook(WP_REST_Request $request) {
    $body = $request->get_body();
    $data = json_decode($body, true);

    if (
        empty($data['event']) ||
        empty($data['object'])
    ) {
        return new WP_REST_Response('Bad request', 400);
    }

    if ($data['event'] !== 'payment.succeeded') {
        return new WP_REST_Response('Ignored', 200);
    }

    $payment = $data['object'];
    $order_id = $payment['metadata']['order_id'] ?? null;

    if (!$order_id) {
        return new WP_REST_Response('No order_id', 200);
    }

    $order = wc_get_order((int)$order_id);
    if (!$order) {
        return new WP_REST_Response('Order not found', 200);
    }

    if ($order->is_paid()) {
        return new WP_REST_Response('Already paid', 200);
    }

    $order->payment_complete($payment['id']);

    $order->add_order_note('Оплата подтверждена YooKassa');

    return new WP_REST_Response('OK', 200);
}