<?php
add_action('wp_ajax_arismed_invoice_submit', 'arismed_invoice_submit');

function arismed_invoice_submit()
{
    if (!function_exists('WC')) {
        wp_send_json_error(['message' => 'Woo not loaded'], 500);
    }

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Auth required'], 403);
    }

    if (
        empty($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'arismed_invoice_submit')
    ) {
        wp_send_json_error(['message' => 'Invalid nonce'], 403);
    }

    if (!WC()->session) {
        wp_send_json_error(['message' => 'Session unavailable'], 500);
    }

    $intent = sanitize_text_field(wp_unslash($_POST['intent'] ?? ''));

    if (!$intent) {
        wp_send_json_error(['message' => 'Invalid intent'], 400);
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
        wp_send_json_error(['message' => 'Session invalid'], 403);
    }

    if (!arismed_can_create_invoice($user_id)) {
        wp_send_json_error([
            'code' => 'INVOICE_LIMIT',
            'message' => 'Превышено количество запросов счета'
        ], 400);
    }

    $order = wc_create_order();

    if (is_wp_error($order)) {
        wp_send_json_error(['message' => 'Order create failed'], 500);
    }

    foreach (WC()->cart->get_cart() as $item) {
        $product = wc_get_product($item['product_id']);
        if ($product) {
            $order->add_product($product, $item['quantity']);
        }
    }

    $checkout = $session['checkout'] ?? [];

    foreach ($checkout as $key => $value) {
        if ($value !== '') {
            $order->update_meta_data($key, $value);
        }
    }

    $order->update_meta_data('organization', sanitize_text_field(wp_unslash($_POST['organization'] ?? '')));
    $order->update_meta_data('inn', sanitize_text_field(wp_unslash($_POST['inn'] ?? '')));
    $order->update_meta_data('kpp', sanitize_text_field(wp_unslash($_POST['kpp'] ?? '')));
    $order->update_meta_data(
        'organization_address',
        sanitize_text_field(wp_unslash($_POST['organization_address'] ?? ''))
    );

    $order->set_customer_id($user_id);
    $order->set_payment_method('invoice');
    $order->set_payment_method_title('Счет');
    $order->set_status('pending');

    $order->calculate_totals();
    $order->save();

    arismed_inc_invoice_counter($user_id);

    WC()->session->__unset($session_key);
    WC()->cart->empty_cart();

    wp_send_json_success([
        'redirect' => add_query_arg(
            [
                'order_id' => $order->get_id(),
                'key' => $order->get_order_key(),
            ],
            home_url('/thankyou/')
        ),
    ]);
}
